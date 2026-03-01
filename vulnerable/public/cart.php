<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

if (!isset($_SESSION["cart"])) { $_SESSION["cart"] = []; } 


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  foreach (($_POST["qty"] ?? []) as $id => $qty) {
    $id = (int)$id;
    $qty = (int)$qty;

    if ($qty <= 0) {
      unset($_SESSION["cart"][$id]);
    } else {
      $_SESSION["cart"][$id] = $qty;
    }
  }
  header("Location: cart.php");
  exit;
}

$cart = $_SESSION["cart"];
$items = [];
$total = 0.0;

if (count($cart) > 0) {
  $ids = implode(",", array_map("intval", array_keys($cart)));
  $stmt = $pdo->query("SELECT id, name, price FROM shop_items WHERE id IN ($ids)");
  $items = $stmt->fetchAll();

  foreach ($items as $it) {
    $qty = $cart[(int)$it["id"]] ?? 0;
    $total += ((float)$it["price"]) * $qty;
  }
}
?>

<div class="card">
  <h1>Your basket</h1>
 <p>Review your items before checkout.</p>
</div>

<br>

<?php if (count($cart) === 0): ?>
  <div class="card"><p>Your basket is empty.</p></div>
<?php else: ?>
  <form method="post" class="card">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th style="text-align:left; padding:8px;">Item</th>
          <th style="text-align:left; padding:8px;">Price</th>
          <th style="text-align:left; padding:8px;">Qty</th>
          <th style="text-align:left; padding:8px;">Line</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): 
          $id = (int)$it["id"];
          $qty = (int)($cart[$id] ?? 0);
          $line = ((float)$it["price"]) * $qty;
        ?>
        <tr>
          <td style="padding:8px;"><?php echo htmlspecialchars($it["name"], ENT_QUOTES, "UTF-8"); ?></td>
          <td style="padding:8px;">£<?php echo number_format((float)$it["price"], 2); ?></td>
          <td style="padding:8px; width:120px;">
            <input type="number" name="qty[<?php echo $id; ?>]" min="0" value="<?php echo $qty; ?>" style="width:80px;">
          </td>
          <td style="padding:8px;">£<?php echo number_format($line, 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <hr>
    <p><strong>Total: £<?php echo number_format($total, 2); ?></strong></p>
    <p><a class="pill" href="checkout.php">Go to checkout</a></p>

    <button type="submit">Update basket</button>
    <button type="button" disabled>Checkout (stub)</button>
  </form>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
