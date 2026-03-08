<?php
function oauth_config(): array {
  return [
    "client_id" => "catsathome-client",
    "client_secret" => "catsathome-secret",
    "authorize_url" => "/mock_oauth_provider/authorize.php",
    "token_url" => "/mock_oauth_provider/token.php",
    "redirect_uri" => "/oauth/callback.php"
  ];
}

function build_url(string $path, array $params = []): string {
  $qs = http_build_query($params);
  return $qs ? $path . "?" . $qs : $path;
}