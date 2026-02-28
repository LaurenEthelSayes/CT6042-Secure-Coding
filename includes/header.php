<?php
header("Content-Type: text/html; charset=utf-8");
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$isLoggedIn = isset($_SESSION["user"]);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cats@Home</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
<header class="site-header">
  <div class="brand">
    <div class="logo">??</div>
    <div>
      <div class="brand-name">Cats@Home</div>
      <div class="brand-tag">Find, adopt, shop, and share. Soft paws only.</div>
    </div>
  </div>

  <nav class="nav">
    <a href="home.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="upcycled.php">Upcycled</a>
    <a href="forum.php">Forum</a>
    <a href="faq.php">FAQ</a>
    <a href="contact.php">Contact</a>

    <?php if (!$isLoggedIn): ?>
      <a class="pill" href="login.php">Login</a>
      <a class="pill" href="register.php">Register</a>
    <?php else: ?>
      <span class="hello">Hi, <?php echo htmlspecialchars($_SESSION["user"], ENT_QUOTES, "UTF-8"); ?>!</span>
      <a class="pill" href="logout.php">Logout</a>
    <?php endif; ?>
  </nav>
</header>

<main class="container">
