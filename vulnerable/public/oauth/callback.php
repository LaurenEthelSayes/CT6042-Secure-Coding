<?php
require_once "../../includes/db.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$code = $_GET["code"] ?? "";
if ($code === "") {
  header("Location: ../login.php?err=oauth");
  exit;
}


$store = __DIR__ . "/../mock_oauth_provider/codes.json";
$data = file_exists($store) ? json_decode(file_get_contents($store), true) : [];
$user = $data[$code] ?? null;

if (!$user) {
  header("Location: ../login.php?err=oauth");
  exit;
}

$profiles = [
  "molly" => ["username" => "molly", "email" => "molly@catbook.local"],
  "admin" => ["username" => "admin", "email" => "admin@catbook.local"],
  "test"  => ["username" => "test",  "email" => "test@catbook.local"]
];

$profile = $profiles[$user] ?? $profiles["molly"];
$email = $profile["email"];
$username = $profile["username"];


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

$_SESSION["user_id"] = (int)$existing["id"];
$_SESSION["user"] = $existing["username"];
$_SESSION["role"] = $existing["role"];

header("Location: ../shop.php");
exit;
