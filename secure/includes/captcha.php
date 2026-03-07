<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function captcha_generate_question(): array {
  $a = random_int(1, 9);
  $b = random_int(1, 9);

  $_SESSION["captcha_answer"] = (string)($a + $b);
  $_SESSION["captcha_question"] = "What is $a + $b ?";

  return [
    "question" => $_SESSION["captcha_question"]
  ];
}

function captcha_verify(string $input): bool {
  $expected = $_SESSION["captcha_answer"] ?? null;
  $provided = trim($input);

  unset($_SESSION["captcha_answer"], $_SESSION["captcha_question"]);

  return $expected !== null && $provided === $expected;
}