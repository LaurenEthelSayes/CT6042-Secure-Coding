<?php
header("Content-Type: application/json; charset=utf-8");

$code = $_GET["code"] ?? "";
$store = __DIR__ . "/codes.json";
$data = file_exists($store) ? json_decode(file_get_contents($store), true) : [];

$user = $data[$code] ?? null;
if (!$user) {
  echo json_encode(["error" => "invalid_code"]);
  exit;
}

$profiles = [
  "molly" => ["username" => "molly", "email" => "molly@catbook.local"],
  "admin" => ["username" => "admin", "email" => "admin@catbook.local"],
  "test"  => ["username" => "test",  "email" => "test@catbook.local"]
];

echo json_encode($profiles[$user] ?? ["username" => "molly", "email" => "molly@catbook.local"]);
