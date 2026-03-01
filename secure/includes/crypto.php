<?php

function crypto_des_encrypt(string $plaintext): string {
  $key = "catskey1"; 
  $cipher = "DES-ECB"; 

  $ct = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA);
  if ($ct === false) {
    return "";
  }
  return base64_encode($ct);
}

function crypto_des_decrypt(string $token): string {
  $key = "catskey1";
  $cipher = "DES-ECB";

  $raw = base64_decode($token, true);
  if ($raw === false) {
    return "";
  }

  $pt = openssl_decrypt($raw, $cipher, $key, OPENSSL_RAW_DATA);
  return ($pt === false) ? "" : $pt;
}
