<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

$items = $pdo->query("SELECT id, name, description, price FROM shop_items ORDER BY id DESC")->fetchAll();
?>

<div class="card">
  <h1>Shop</h1>
  <p><strong>Members-only</strong> cat goodies: toys, bowls, treats, scratching posts.</p>
</div>

<br>

<?php if (count($items) === 0): ?>
  <div class="card">
    <p>No items yet. (We can seed some in phpMyAdmin.)</p>
  </div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($items as $item): ?>
      <div class="card">
        <h2><?php echo htmlspecialchars($item["name"], ENT_QUOTES, "UTF-8"); ?></h2>
        <p><?php echo htmlspecialchars($item["description"], ENT_QUOTES, "UTF-8"); ?></p>
        <p><strong>£<?php echo number_format((float)$item["price"], 2); ?></strong></p>
        <button type="button" disabled>Add to basket</button>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
