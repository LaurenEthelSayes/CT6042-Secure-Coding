<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

$error = "";
$ok = "";

// Create listing
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = trim($_POST["title"] ?? "");
  $description = trim($_POST["description"] ?? "");
  $price = trim($_POST["price"] ?? "");

  if ($title === "" || $description === "" || $price === "") {
    $error = "Please fill in all fields.";
  } else {
    try {
      $stmt = $pdo->prepare("INSERT INTO listings (user_id, title, description, price) VALUES (?, ?, ?, ?)");
      $stmt->execute([ (int)$_SESSION["user_id"], $title, $description, (float)$price ]);
      $ok = "Listing posted!";
    } catch (Exception $e) {
      $error = "Could not post listing.";
    }
  }
}

// Fetch listings newest first
$stmt = $pdo->query("
  SELECT l.id, l.title, l.description, l.price, l.created_at, u.username
  FROM listings l
  JOIN users u ON u.id = l.user_id
  ORDER BY l.id DESC
");
$listings = $stmt->fetchAll();
?>

<div class="card">
  <h1>Upcycled</h1>
  <p>Buy and sell pre-loved cat items. Keep it cute, keep it kind.</p>
</div>

<br>

<div class="card">
  <h2>Create listing</h2>

  <?php if ($error): ?>
    <p style="color:#b00020;"><strong><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>

  <?php if ($ok): ?>
    <p style="color:#0b6;"><strong><?php echo htmlspecialchars($ok, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>

  <form method="post" action="upcycled.php">
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
        <button type="button" disabled>Buy (stub)</button>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
