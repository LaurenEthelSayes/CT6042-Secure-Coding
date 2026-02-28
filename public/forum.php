<?php require_once "../includes/header.php"; ?>

<div class="card">
  <h1>Forum</h1>
  <p>Ask questions, share wins, post pics of tiny toe beans.</p>

  <h2>New post</h2>
  <form method="post" action="forum.php">
    <label>Topic</label><br>
    <input type="text" name="topic" required><br><br>

    <label>Message</label><br>
    <textarea name="message" rows="5" required></textarea><br><br>

    <button type="submit">Post</button>
  </form>

  <hr>

  <h2>Recent posts</h2>
  <p>(Posts will appear here once we add storage.)</p>
</div>

<?php require_once "../includes/footer.php"; ?>
