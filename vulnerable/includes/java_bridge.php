<?php
function java_decentralisation_run(string $payload): string {
  $jar = realpath(__DIR__ . "/../java/decentralisation.jar");
  if (!$jar || !file_exists($jar)) {
    return "Java Decentralisation module not installed.";
  }

  $cmd = "java -jar " . escapeshellarg($jar) . " " . escapeshellarg($payload);
  return (string)@shell_exec($cmd);
}
