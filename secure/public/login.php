<?php
require_once "../includes/header.php";
require_once "../includes/db.php";
require_once "../includes/captcha.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  if (!captcha_verify($_POST["captcha"] ?? "")) {
    $error = "CAPTCHA failed.";
  } else {
    $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
      session_regenerate_id(true);
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user"] = $user["username"];
      $_SESSION["role"] = $user["role"];
      header("Location: shop.php");
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  }
}

$captcha = captcha_generate_question();
?>

<div class="card">
  <h1>Login</h1>
  <p>Welcome back. Please sign in.</p>

  <?php if (isset($_GET["registered"])): ?>
    <p style="color:#0b6;"><strong>Account created. Please log in.</strong></p>
  <?php endif; ?>

  <?php if ($error): ?>
    <p style="color:#b00020;"><strong><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>

  <form method="post" action="login.php">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>CAPTCHA: <?php echo htmlspecialchars($captcha["question"], ENT_QUOTES, "UTF-8"); ?></label><br>
    <input type="text" name="captcha" required><br><br>

    <button type="submit">Login</button>
  </form>

  <p style="margin-top:14px;"><strong>Or</strong></p>
  <p><a class="pill" href="oauth/login.php">Login with CatBook</p>

  <p style="margin-top:14px;">No account? <a href="register.php">Register here</a>.</p>
</div>

<?php require_once "../includes/footer.php"; ?>