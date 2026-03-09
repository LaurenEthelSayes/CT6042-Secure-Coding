<?php

function crypto_aes_encrypt(string $plaintext): string {
  $key = random_bytes(32); 
  $iv = random_bytes(16);  
  $cipher = "AES-256-CBC";

  $ct = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  if ($ct === false) {
    return "";
  }

  return base64_encode($iv . $ct);
}

function crypto_aes_decrypt(string $token, string $key): string {
  $cipher = "AES-256-CBC";

  $raw = base64_decode($token, true);
  if ($raw === false || strlen($raw) < 17) {
    return "";
  }

  $iv = substr($raw, 0, 16);
  $ct = substr($raw, 16);

  $pt = openssl_decrypt($ct, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  return ($pt === false) ? "" : $pt;
}