<?php
header("Content-Type: application/json; charset=utf-8");

$code = $_GET["code"] ?? "";
$redirectUri = $_GET["redirect_uri"] ?? "";

if ($code === "" || $redirectUri === "") {
  echo json_encode(["error" => "invalid_request"]);
  exit;
}

$store = __DIR__ . "/codes.json";
$data = file_exists($store) ? json_decode(file_get_contents($store), true) : [];

if (!is_array($data)) {
  $data = [];
}

$record = $data[$code] ?? null;

if (!is_array($record)) {
  echo json_encode(["error" => "invalid_code"]);
  exit;
}

if (($record["used"] ?? true) === true) {
  echo json_encode(["error" => "code_already_used"]);
  exit;
}

if (($record["redirect_uri"] ?? "") !== $redirectUri) {
  echo json_encode(["error" => "redirect_uri_mismatch"]);
  exit;
}

$issuedAt = (int)($record["issued_at"] ?? 0);
if ($issuedAt <= 0 || (time() - $issuedAt) > 120) {
  echo json_encode(["error" => "code_expired"]);
  exit;
}

$user = $record["user"] ?? null;
if (!$user) {
  echo json_encode(["error" => "invalid_code"]);
  exit;
}

$profiles = [
  "molly" => ["username" => "molly", "email" => "molly@catbook.local"],
  "admin" => ["username" => "admin", "email" => "admin@catbook.local"],
  "test"  => ["username" => "test",  "email" => "test@catbook.local"]
];

$profile = $profiles[$user] ?? null;
if (!$profile) {
  echo json_encode(["error" => "invalid_user"]);
  exit;
}

$data[$code]["used"] = true;
file_put_contents($store, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode($profile);