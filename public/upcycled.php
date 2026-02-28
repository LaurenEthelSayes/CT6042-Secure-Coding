<?php require_once "../includes/header.php"; ?>

<div class="card">
  <h1>Upcycled</h1>
  <p>Buy and sell pre-loved cat items. Keep it cute, keep it kind.</p>

  <h2>Create listing</h2>
  <form method="post" action="upcycled.php">
    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description</label><br>
    <textarea name="description" rows="4" required></textarea><br><br>

    <label>Price (£)</label><br>
    <input type="number" name="price" min="0" step="0.01" required><br><br>

    <button type="submit">Post listing</button>
  </form>

  <hr>

  <h2>Listings</h2>
  <p>(Listings will appear here once we add storage.)</p>
</div>

<?php require_once "../includes/footer.php"; ?>
