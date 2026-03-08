<?php
require_once "../../includes/db.php";
require_once "../../includes/oauth_client.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$code = $_GET["code"] ?? "";
$returnedState = $_GET["state"] ?? "";
$expectedState = $_SESSION["oauth_state"] ?? "";

if ($code === "" || $returnedState === "" || $expectedState === "") {
  header("Location: ../login.php?err=oauth");
  exit;
}

if (!hash_equals($expectedState, $returnedState)) {
  unset($_SESSION["oauth_state"]);
  header("Location: ../login.php?err=oauth_state");
  exit;
}

unset($_SESSION["oauth_state"]);

$cfg = oauth_config();

$tokenUrl = build_url($cfg["token_url"], [
  "code" => $code,
  "redirect_uri" => $cfg["redirect_uri"]
]);

$response = @file_get_contents($tokenUrl);
if ($response === false) {
  header("Location: ../login.php?err=oauth");
  exit;
}

$response = trim($response);
$response = preg_replace('/^\xEF\xBB\xBF/', '', $response);

$profile = json_decode($response, true);

if (!is_array($profile) || isset($profile["error"])) {
  header("Location: ../login.php?err=oauth");
  exit;
}

$email = $profile["email"] ?? "";
$username = $profile["username"] ?? "";

if ($email === "" || $username === "") {
  header("Location: ../login.php?err=oauth");
  exit;
}

$stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$existing = $stmt->fetch();

if (!$existing) {
  $hash = password_hash(bin2hex(random_bytes(12)), PASSWORD_BCRYPT);

  $base = $username;
  $final = $base;

  $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM users WHERE username = ?");
  $stmt->execute([$final]);
  $exists = (int)$stmt->fetch()["c"];

  if ($exists > 0) {
    $final = $base . "_catbook";
  }

  $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
  $stmt->execute([$final, $email, $hash]);

  $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $existing = $stmt->fetch();
}

session_regenerate_id(true);

$_SESSION["user_id"] = (int)$existing["id"];
$_SESSION["user"] = $existing["username"];
$_SESSION["role"] = $existing["role"];

header("Location: ../shop.php");
exit;