<?php
$redirect = $_GET["redirect_uri"] ?? "";
$state = $_GET["state"] ?? "";
$error = "";

$allowedRedirects = [
  "/oauth/callback.php"
];

if (!in_array($redirect, $allowedRedirects, true)) {
  http_response_code(400);
  die("Invalid redirect URI.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  $validUsers = [
    "molly" => "CatBook123!",
    "admin" => "Admin123!",
    "test"  => "Test123!"
  ];

  if (!isset($validUsers[$username]) || $validUsers[$username] !== $password) {
    $error = "Invalid username or password.";
  } else {
    if (!in_array($redirect, $allowedRedirects, true)) {
      http_response_code(400);
      die("Invalid redirect URI.");
    }

    $user = $username;
    $code = bin2hex(random_bytes(8));

    $store = __DIR__ . "/codes.json";
    $data = file_exists($store) ? json_decode(file_get_contents($store), true) : [];

    if (!is_array($data)) {
      $data = [];
    }

    $data[$code] = [
      "user" => $user,
      "issued_at" => time(),
      "used" => false,
      "redirect_uri" => $redirect
    ];

    file_put_contents($store, json_encode($data, JSON_PRETTY_PRINT));

    $sep = (str_contains($redirect, "?")) ? "&" : "?";
    header("Location: " . $redirect . $sep . "code=" . urlencode($code) . "&state=" . urlencode($state));
    exit;
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>CatBook Sign in</title>
</head>
<body style="font-family:Arial; padding:30px;">
  <h1>CatBook</h1>
  <p>Sign in to continue to Cats@Home.</p>

  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Continue</button>
  </form>
</body>
</html>