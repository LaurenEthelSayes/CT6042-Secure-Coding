<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/header.php";
require_once "../includes/db.php";

$error = "";

/* New post */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_post"])) {
  $topic = trim($_POST["topic"] ?? "");
  $message = trim($_POST["message"] ?? "");

  if ($topic === "" || $message === "") {
    $error = "Please fill in both fields.";
  } else {
    try {
      $stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, topic, message) VALUES (?, ?, ?)");
      $stmt->execute([(int)$_SESSION["user_id"], $topic, $message]);
      header("Location: forum.php?ok=posted");
      exit;
    } catch (Exception $e) {
      $error = "Could not post message.";
    }
  }
}

/* Reply to a post */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reply_post_id"])) {
  $postId = (int)($_POST["reply_post_id"] ?? 0);
  $replyMsg = trim($_POST["reply_message"] ?? "");

  if ($postId <= 0 || $replyMsg === "") {
    $error = "Reply could not be posted (missing info).";
  } else {
    try {
      $stmt = $pdo->prepare("INSERT INTO forum_replies (post_id, user_id, message) VALUES (?, ?, ?)");
      $stmt->execute([$postId, (int)$_SESSION["user_id"], $replyMsg]);
      header("Location: forum.php?ok=replied");
      exit;
    } catch (Exception $e) {
      $error = "Could not post reply.";
    }
  }
}

/* Fetch posts */
$stmt = $pdo->query("
  SELECT p.id, p.topic, p.message, p.created_at, u.username
  FROM forum_posts p
  JOIN users u ON u.id = p.user_id
  ORDER BY p.id DESC
");
$posts = $stmt->fetchAll();

/* Fetch replies for all posts in one go */
$repliesByPost = [];
if (count($posts) > 0) {
  $ids = implode(",", array_map(fn($p) => (int)$p["id"], $posts));
  $stmt = $pdo->query("
    SELECT r.id, r.post_id, r.message, r.created_at, u.username
    FROM forum_replies r
    JOIN users u ON u.id = r.user_id
    WHERE r.post_id IN ($ids)
    ORDER BY r.id ASC
  ");
  $replies = $stmt->fetchAll();

  foreach ($replies as $r) {
    $pid = (int)$r["post_id"];
    if (!isset($repliesByPost[$pid])) { $repliesByPost[$pid] = []; }
    $repliesByPost[$pid][] = $r;
  }
}
?>

<div class="card">
  <h1>Forum</h1>
  <p>Ask questions, share wins, post pics of tiny toe beans.</p>

  <?php if (isset($_GET["ok"]) && $_GET["ok"] === "posted"): ?>
    <p style="color:#0b6;"><strong>Posted! 🐾</strong></p>
  <?php elseif (isset($_GET["ok"]) && $_GET["ok"] === "replied"): ?>
    <p style="color:#0b6;"><strong>Reply added!</strong></p>
  <?php endif; ?>

  <?php if ($error): ?>
    <p style="color:#b00020;"><strong><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></strong></p>
  <?php endif; ?>
</div>

<br>

<div class="card">
  <h2>New post</h2>
  <form method="post" action="forum.php">
    <input type="hidden" name="create_post" value="1">

    <label>Topic</label><br>
    <input type="text" name="topic" required><br><br>

    <label>Message</label><br>
    <textarea name="message" rows="5" required></textarea><br><br>

    <button type="submit">Post</button>
  </form>
</div>

<br>

<?php if (count($posts) === 0): ?>
  <div class="card"><p>No posts yet. Start the conversation ✨</p></div>
<?php else: ?>
  <div class="grid">
    <?php foreach ($posts as $p): 
      $postId = (int)$p["id"];
      $replies = $repliesByPost[$postId] ?? [];
    ?>
      <div class="card">
        <h2><?php echo htmlspecialchars($p["topic"], ENT_QUOTES, "UTF-8"); ?></h2>
        <p style="color:#6b6b6b; font-size: 13px;">
          Posted by <strong><?php echo htmlspecialchars($p["username"], ENT_QUOTES, "UTF-8"); ?></strong>
          • <?php echo htmlspecialchars($p["created_at"], ENT_QUOTES, "UTF-8"); ?>
        </p>
        <p><?php echo nl2br(htmlspecialchars($p["message"], ENT_QUOTES, "UTF-8")); ?></p>

        <hr>

        <h3 style="margin-top:0;">Replies (<?php echo count($replies); ?>)</h3>

        <?php if (count($replies) === 0): ?>
          <p style="color:#6b6b6b;">No replies yet.</p>
        <?php else: ?>
          <?php foreach ($replies as $r): ?>
            <div style="border:1px solid #f0d6dc; border-radius:14px; padding:10px; margin:10px 0; background:#fff;">
              <p style="color:#6b6b6b; font-size: 13px; margin:0 0 6px 0;">
                <strong><?php echo htmlspecialchars($r["username"], ENT_QUOTES, "UTF-8"); ?></strong>
                • <?php echo htmlspecialchars($r["created_at"], ENT_QUOTES, "UTF-8"); ?>
              </p>
              <p style="margin:0;"><?php echo nl2br(htmlspecialchars($r["message"], ENT_QUOTES, "UTF-8")); ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <h3>Reply</h3>
        <form method="post" action="forum.php">
          <input type="hidden" name="reply_post_id" value="<?php echo $postId; ?>">
          <textarea name="reply_message" rows="3" required></textarea><br><br>
          <button type="submit">Reply</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>
