<?php
require_once "../../includes/auth.php";
require_once "../../includes/oauth_client.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$cfg = oauth_config();

// Create a state value (we will use/validate this properly later)
$state = bin2hex(random_bytes(16));
$_SESSION["oauth_state"] = $state;

// In a real OAuth flow, you would redirect to an external provider.
// For the lab, we redirect to our local mock provider.
$authUrl = build_url($cfg["authorize_url"], [
  "response_type" => "code",
  "client_id" => $cfg["client_id"],
  "redirect_uri" => $cfg["redirect_uri"],
  "state" => $state,
  "scope" => "profile email"
]);

header("Location: " . $authUrl);
exit;
