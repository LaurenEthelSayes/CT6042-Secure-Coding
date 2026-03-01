<?php
require_once "../includes/header.php";
require_once "../includes/db.php";

$users = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY id")->fetchAll();
$items = $pdo->query("SELECT id, name, price FROM shop_items ORDER BY id")->fetchAll();
?>

<div class="card">
  <h1>DB Test</h1>

  <h2>Users</h2>
  <pre><?php print_r($users); ?></pre>

  <h2>Shop items</h2>
  <pre><?php print_r($items); ?></pre>
</div>

<?php require_once "../includes/footer.php"; ?>
