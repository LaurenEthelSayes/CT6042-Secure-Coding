<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function captcha_generate_question(): array {
  $a = random_int(1, 9);
  $b = random_int(1, 9);
  $answer = (string)($a + $b);

  return [
    "question" => "What is $a + $b ?",
    "answer" => $answer
  ];
}

function captcha_verify(string $input): bool {
  $expected = $_POST["captcha_expected"] ?? "";
  return trim($input) === trim($expected);
}