<?php
require_once "../../includes/auth.php";
require_once "../../includes/oauth_client.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$cfg = oauth_config();

$state = bin2hex(random_bytes(16));
$_SESSION["oauth_state"] = $state;

$authUrl = build_url($cfg["authorize_url"], [
  "response_type" => "code",
  "client_id" => $cfg["client_id"],
  "redirect_uri" => $cfg["redirect_uri"],
  "state" => $state,
  "scope" => "profile email"
]);

header("Location: " . $authUrl);
exit;
