<?php
require_once "../../includes/db.php";
require_once "../../includes/oauth_client.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$code = $_GET["code"] ?? "";
$state = $_GET["state"] ?? "";

// For now: just basic plumbing.
// We'll harden the validations in the "secure iteration".
if ($code === "") {
  header("Location: ../login.php?err=oauth");
  exit;
}

// MOCK exchange: call our local token endpoint using file_get_contents (simple lab plumbing)
$cfg = oauth_config();
$tokenUrl = build_url($cfg["token_url"], [
  "code" => $code,
  "client_id" => $cfg["client_id"],
  "client_secret" => $cfg["client_secret"]
]);

$tokenJson = @file_get_contents("http://localhost" . $tokenUrl);
$token = $tokenJson ? json_decode($tokenJson, true) : null;

if (!$token || empty($token["email"]) || empty($token["username"])) {
  header("Location: ../login.php?err=oauth");
  exit;
}

$email = $token["email"];
$username = $token["username"];

// Create or find user
$stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
  $hash = password_hash(bin2hex(random_bytes(12)), PASSWORD_BCRYPT);
  $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
  $stmt->execute([$username, $email, $hash]);

  $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();
}

// Log in
$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["user"] = $user["username"];
$_SESSION["role"] = $user["role"];

header("Location: ../shop.php");
exit;
