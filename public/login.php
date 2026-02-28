<?php require_once "../includes/header.php"; ?>

<div class="card">
  <h1>Login</h1>
  <p>Welcome back. Please sign in.</p>

  <form method="post" action="login.php">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>

  <p style="margin-top:14px;">No account? <a href="register.php">Register here</a>.</p>
</div>

<?php require_once "../includes/footer.php"; ?>
