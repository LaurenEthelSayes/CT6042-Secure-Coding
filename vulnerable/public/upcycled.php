<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

$error = "";
$ok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_listing"])) {
  $title = trim($_POST["title"] ?? "");
  $description = trim($_POST["description"] ?? "");
  $price = trim($_POST["price"] ?? "");

  if ($title === "" || $description === "" || $price === "") {
    $error = "Please fill in all fields.";
  } else {
    try {
      $stmt = $pdo->prepare("INSERT INTO listings (user_id, title, description, price) VALUES (?, ?, ?, ?)");
      $stmt->execute([(int)$_SESSION["user_id"], $title, $description, (float)$price]);
      header("Location: upcycled.php?ok=posted");
      exit;
    } catch (Exception $e) {
      $error = "Could not post listing.";
    }
  }
}

$stmt = $pdo->query("
  SELECT 
    l.id, l.title, l.description, l.price, l.created_at,
    u.username,
    p.id AS purchase_id
  FROM listings l
  JOIN users u ON u.id = l.user_id
  LEFT JOIN listing_purchases p ON p.listing_id = l.id
  ORDER BY l.id DESC
");
$listings = $stmt->fetchAll();
?>

<div class="card">
  <h1>Upcycled</h1>
  <p>Buy and sell pre-loved cat items. Keep it cute, keep it kind.</p>

  <?php if (isset($_GET["ok"]) && $_GET["ok"] === "bought"): ?>
    <p style="color:#0b6;"><strong>Purchased! 🎉</strong></p>
  <?php elseif (isset($_GET["ok"]) && $_GET["ok"] === "posted"): ?>
    <p style="color:#0b6;"><strong>Listing posted! ✨</strong></p>
  <?php endif; ?>

  <?php if (isset($_GET["err"])): ?>
    <p style="color:#b00020;"><strong>
      <?php
        $m = $_GET["err"];
        echo $m === "sold" ? "Sorry, that listing has already been sold."
           : ($m === "own" ? "You can’t buy your own listing."
           : ($m === "missing" ? "Listing not found."
           : "Something went wrong."));
      ?>
    </strong></p>
  <?php endif; ?>
</div>

<br>

<div class="card">
  <h2>Create listing</h2>

  <?php if ($error): ?>
    <p style="color:#b00020;"><strong><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>

  <form method="post" action="upcycled.php">
    <input type="hidden" name="create_listing" value="1">

    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="4" required></textarea><br><br>

    <label>Price (£)</label><br>
    <input type="number" name="price" min="0" step="0.01" required><br><br>

    <button type="submit">Post listing</button>
  </form>
</div>

<br>

<?php if (count($listings) === 0): ?>
  <div class="card">
    <p>No listings yet. Be the first to post something ✨</p>
  </div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($listings as $l): ?>
      <div class="card">
        <h2><?php echo htmlspecialchars($l["title"], ENT_QUOTES, "UTF-8"); ?></h2>

        <p style="color:#6b6b6b; font-size: 13px;">
          Posted by <strong><?php echo htmlspecialchars($l["username"], ENT_QUOTES, "UTF-8"); ?></strong>
          • <?php echo htmlspecialchars($l["created_at"], ENT_QUOTES, "UTF-8"); ?>
        </p>

        <p><?php echo nl2br(htmlspecialchars($l["description"], ENT_QUOTES, "UTF-8")); ?></p>

        <p><strong>£<?php echo number_format((float)$l["price"], 2); ?></strong></p>

        <?php if (!empty($l["purchase_id"])): ?>
          <button type="button" disabled>Sold</button>
        <?php else: ?>
          <form method="post" action="listing_buy.php" style="margin-top:10px;">
            <input type="hidden" name="listing_id" value="<?php echo (int)$l["id"]; ?>">
            <button type="submit">Buy</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
