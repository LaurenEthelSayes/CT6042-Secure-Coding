<?php
function native_overflow_run(string $input): array {
  $exe = realpath(__DIR__ . "/../native/bufferoverflow50.exe");
  if (!$exe || !file_exists($exe)) {
    return ["ok" => false, "out" => "Native EXE not found: native/bufferoverflow50.exe"];
  }

  $descriptorspec = [
    0 => ["pipe", "r"],  
    1 => ["pipe", "w"],  
    2 => ["pipe", "w"]   
  ];

  $process = @proc_open('"' . $exe . '"', $descriptorspec, $pipes, dirname($exe));

  if (!is_resource($process)) {
    return ["ok" => false, "out" => "Failed to start native EXE (proc_open blocked?)"];
  }

  fwrite($pipes[0], $input);
  fclose($pipes[0]);

  $stdout = stream_get_contents($pipes[1]);
  fclose($pipes[1]);

  $stderr = stream_get_contents($pipes[2]);
  fclose($pipes[2]);

  $exitCode = proc_close($process);

  $combined = $stdout;
  if ($stderr !== "") {
    $combined .= "\n[stderr]\n" . $stderr;
  }
  $combined .= "\n[error count] " . $exitCode;

  return ["ok" => true, "out" => $combined];
}