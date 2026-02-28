<?php
require_once "../includes/header.php";
require_once "../includes/db.php";

$error = "";
$ok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($username === "" || $email === "" || $password === "") {
    $error = "All fields are required.";
  } else {
    $hash = password_hash($password, PASSWORD_BCRYPT);

    try {
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");
      $stmt->execute([$username, $email, $hash]);
      $ok = "Account created. You can now login.";
    } catch (Exception $e) {
      $error = "Account creation failed (username/email may already exist).";
    }
  }
}
?>

<div class="card">
  <h1>Create account</h1>
  <p>Join Cats@Home to access the shop, sell upcycled items, and post in the forum.</p>

  <?php if ($error): ?>
    <p style="color:#b00020;"><strong><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>

  <?php if ($ok): ?>
    <p style="color:#0b6;"><strong><?php echo htmlspecialchars($ok, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>

  <form method="post" action="register.php">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Create account</button>
  </form>

  <p style="margin-top:14px;">Already got an account? <a href="login.php">Login</a>.</p>
</div>

<?php require_once "../includes/footer.php"; ?>
