<?php
function native_echo(string $input): string {
  $exe = realpath(__DIR__ . "/../native/echo_stub.exe");
  if (!$exe || !file_exists($exe)) {
    return "Native component not installed";
  }

  
  $cmd = escapeshellarg($exe) . " " . escapeshellarg($input);
  return (string)@shell_exec($cmd);
}
