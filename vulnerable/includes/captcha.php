<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function captcha_generate_question(): array {
  $a = random_int(1, 9);
  $b = random_int(1, 9);
  $_SESSION["captcha_answer"] = (string)($a + $b);
  return ["question" => "What is $a + $b ?", "answer" => $_SESSION["captcha_answer"]];
}

function captcha_verify(string $input): bool {
  $expected = $_SESSION["captcha_answer"] ?? null;
  return $expected !== null && trim($input) === $expected;
}
