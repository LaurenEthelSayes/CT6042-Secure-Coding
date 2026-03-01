<?php
// OAuth scaffolding (mock provider for local lab)
function oauth_config(): array {
  return [
    "client_id" => "catsathome-client",
    "client_secret" => "catsathome-secret",
    "authorize_url" => "/CT6042-Secure-Coding/public/mock_oauth_provider/authorize.php",
    "token_url" => "/CT6042-Secure-Coding/public/mock_oauth_provider/token.php",
    "redirect_uri" => "/CT6042-Secure-Coding/public/oauth/callback.php"
  ];
}

function build_url(string $path, array $params = []): string {
  $qs = http_build_query($params);
  return $qs ? $path . "?" . $qs : $path;
}
