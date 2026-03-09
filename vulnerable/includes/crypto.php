<?php

function crypto_aes_encrypt(string $plaintext): string {
  $key = "catscatscatscats"; 
  $cipher = "AES-128-CBC";
  $iv = "1234567890abcdef";  

  $ct = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  if ($ct === false) {
    return "";
  }
  return base64_encode($ct);
}

function crypto_aes_decrypt(string $token): string {
  $key = "catscatscatscats";
  $cipher = "AES-128-CBC";
  $iv = "1234567890abcdef";

  $raw = base64_decode($token, true);
  if ($raw === false) {
    return "";
  }

  $pt = openssl_decrypt($raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  return ($pt === false) ? "" : $pt;
}