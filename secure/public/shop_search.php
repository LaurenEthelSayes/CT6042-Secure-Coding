<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

$q = trim($_GET["q"] ?? "");
$search = "%" . $q . "%";

$stmt = $pdo->prepare("
  SELECT id, name, description, price
  FROM shop_items
  WHERE name LIKE ? OR description LIKE ?
  ORDER BY id DESC
");
$stmt->execute([$search, $search]);
$items = $stmt->fetchAll();
?>

<div class="card">
  <h1>Shop Search</h1>
  <form method="get" action="shop_search.php">
    <input type="text" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, "UTF-8"); ?>" placeholder="Search shop items...">
    <button type="submit">Search</button>
  </form>
  <p style="color:#6b6b6b; font-size:13px;"></p>
</div>

<br>

<?php if (count($items) === 0): ?>
  <div class="card"><p>No results.</p></div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($items as $item): ?>
      <div class="card">
        <h2><?php echo htmlspecialchars($item["name"], ENT_QUOTES, "UTF-8"); ?></h2>
        <p><?php echo htmlspecialchars($item["description"], ENT_QUOTES, "UTF-8"); ?></p>
        <p><strong>£<?php echo number_format((float)$item["price"], 2); ?></strong></p>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
