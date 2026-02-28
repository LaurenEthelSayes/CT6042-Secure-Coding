<?php require_once "../includes/header.php"; ?>

<div class="card">
  <h1>Create account</h1>
  <p>Join Cats@Home to access the shop, sell upcycled items, and post in the forum.</p>

  <form method="post" action="register.php">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Create account</button>
  </form>
</div>

<?php require_once "../includes/footer.php"; ?>
