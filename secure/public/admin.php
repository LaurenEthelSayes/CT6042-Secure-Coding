<?php
require_once "../includes/header.php";
require_once "../includes/db.php";

$userCount = (int)$pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()["c"];
$orderCount = (int)$pdo->query("SELECT COUNT(*) AS c FROM orders")->fetch()["c"];
$listingCount = (int)$pdo->query("SELECT COUNT(*) AS c FROM listings")->fetch()["c"];
$postCount = (int)$pdo->query("SELECT COUNT(*) AS c FROM forum_posts")->fetch()["c"];

$users = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY id DESC LIMIT 20")->fetchAll();
$orders = $pdo->query("SELECT id, user_id, total, basket_json, created_at FROM orders ORDER BY id DESC LIMIT 20")->fetchAll();
$listings = $pdo->query("SELECT id, user_id, title, price, created_at FROM listings ORDER BY id DESC LIMIT 20")->fetchAll();
$posts = $pdo->query("SELECT id, user_id, topic, created_at FROM forum_posts ORDER BY id DESC LIMIT 20")->fetchAll();
?>

<div class="card">
  <h1>Admin Panel</h1>
</div>

<br>

<div class="grid">
  <div class="card">
    <h2>Quick stats</h2>
    <ul>
      <li>Users: <strong><?php echo $userCount; ?></strong></li>
      <li>Orders: <strong><?php echo $orderCount; ?></strong></li>
      <li>Upcycled listings: <strong><?php echo $listingCount; ?></strong></li>
      <li>Forum posts: <strong><?php echo $postCount; ?></strong></li>
    </ul>
  </div>

  <div class="card">
    <h2>Users (latest 20)</h2>
    <?php if (count($users) === 0): ?>
      <p>No users found.</p>
    <?php else: ?>
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left; padding:6px;">ID</th>
            <th style="text-align:left; padding:6px;">Username</th>
            <th style="text-align:left; padding:6px;">Email</th>
            <th style="text-align:left; padding:6px;">Role</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td style="padding:6px;"><?php echo (int)$u["id"]; ?></td>
              <td style="padding:6px;"><?php echo htmlspecialchars($u["username"], ENT_QUOTES, "UTF-8"); ?></td>
              <td style="padding:6px;"><?php echo htmlspecialchars($u["email"], ENT_QUOTES, "UTF-8"); ?></td>
              <td style="padding:6px;"><?php echo htmlspecialchars($u["role"], ENT_QUOTES, "UTF-8"); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <div class="card">
    <h2>Orders (latest 20)</h2>
    <?php if (count($orders) === 0): ?>
      <p>No orders found.</p>
    <?php else: ?>
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left; padding:6px;">Order</th>
            <th style="text-align:left; padding:6px;">User</th>
            <th style="text-align:left; padding:6px;">Total</th>
            <th style="text-align:left; padding:6px;">Created</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td style="padding:6px;">#<?php echo (int)$o["id"]; ?></td>
              <td style="padding:6px;"><?php echo (int)$o["user_id"]; ?></td>
              <td style="padding:6px;">£<?php echo number_format((float)$o["total"], 2); ?></td>
              <td style="padding:6px;"><?php echo htmlspecialchars($o["created_at"], ENT_QUOTES, "UTF-8"); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <details style="margin-top:10px;">
        <summary>Show raw basket JSON</summary>
        <pre><?php echo htmlspecialchars(json_encode($orders, JSON_PRETTY_PRINT), ENT_QUOTES, "UTF-8"); ?></pre>
      </details>
    <?php endif; ?>
  </div>

  <div class="card">
    <h2>Upcycled listings (latest 20)</h2>
    <?php if (count($listings) === 0): ?>
      <p>No listings found.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($listings as $l): ?>
          <li>
            #<?php echo (int)$l["id"]; ?> • User <?php echo (int)$l["user_id"]; ?> •
            <?php echo htmlspecialchars($l["title"], ENT_QUOTES, "UTF-8"); ?> •
            £<?php echo number_format((float)$l["price"], 2); ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <div class="card">
    <h2>Forum posts (latest 20)</h2>
    <?php if (count($posts) === 0): ?>
      <p>No posts found.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($posts as $p): ?>
          <li>
            #<?php echo (int)$p["id"]; ?> • User <?php echo (int)$p["user_id"]; ?> •
            <?php echo htmlspecialchars($p["topic"], ENT_QUOTES, "UTF-8"); ?>
            (<?php echo htmlspecialchars($p["created_at"], ENT_QUOTES, "UTF-8"); ?>)
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>

<?php require_once "../includes/footer.php"; ?>
