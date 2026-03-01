<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

if (!isset($_SESSION["cart"])) { $_SESSION["cart"] = []; }
$cart = $_SESSION["cart"];

$items = [];
$total = 0.0;

if (count($cart) > 0) {
  $ids = implode(",", array_map("intval", array_keys($cart)));
  $stmt = $pdo->query("SELECT id, name, price FROM shop_items WHERE id IN ($ids)");
  $items = $stmt->fetchAll();

  foreach ($items as $it) {
    $qty = (int)($cart[(int)$it["id"]] ?? 0);
    $total += ((float)$it["price"]) * $qty;
  }
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $basketJson = json_encode($cart);

  $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, basket_json) VALUES (?, ?, ?)");
  $stmt->execute([(int)$_SESSION["user_id"], (float)$total, $basketJson]);

  $_SESSION["cart"] = [];
  $msg = "Order placed (stub).";
}
?>

<div class="card">
  <h1>Checkout</h1>
  <p>Review your basket and place an order.</p>

  <?php if ($msg): ?>
    <p style="color:#0b6;"><strong><?php echo htmlspecialchars($msg, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>
</div>

<br>

<?php if (count($cart) === 0): ?>
  <div class="card"><p>Your basket is empty.</p></div>
<?php else: ?>
  <div class="card">
    <h2>Order summary</h2>
    <ul>
      <?php foreach ($items as $it):
        $qty = (int)($cart[(int)$it["id"]] ?? 0);
      ?>
        <li><?php echo htmlspecialchars($it["name"], ENT_QUOTES, "UTF-8"); ?> × <?php echo $qty; ?></li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: £<?php echo number_format($total, 2); ?></strong></p>

    <form method="post" action="checkout.php">
      <button type="submit">Place order</button>
    </form>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
