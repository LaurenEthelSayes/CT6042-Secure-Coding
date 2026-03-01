<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

$stmt = $pdo->prepare("SELECT id, total, payment_token, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([(int)$_SESSION["user_id"]]);
$orders = $stmt->fetchAll();
?>

<div class="card">
  <h1>Your orders</h1>
  <p>Order history</p>
</div>

<br>

<?php if (count($orders) === 0): ?>
  <div class="card"><p>No orders yet.</p></div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($orders as $o): ?>
      <div class="card">
        <h2>Order #<?php echo (int)$o["id"]; ?></h2>
        <p><strong>Total:</strong> £<?php echo number_format((float)$o["total"], 2); ?></p>
        <p style="color:#6b6b6b; font-size:13px;"><?php echo htmlspecialchars($o["created_at"], ENT_QUOTES, "UTF-8"); ?></p>

        <?php if (!empty($o["payment_token"])): ?>
          <details style="margin-top:10px;">
            <summary>Payment token (DES)</summary>
            <pre><?php echo htmlspecialchars($o["payment_token"], ENT_QUOTES, "UTF-8"); ?></pre>
          </details>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>