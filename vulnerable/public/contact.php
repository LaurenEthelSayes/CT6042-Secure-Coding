<?php require_once "../includes/header.php"; ?>

<div class="card">
  <h1>Contact us</h1>
  <p>Send a message to the Cats@Home team.</p>

  <form method="post" action="contact.php">
    <label>Your email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Message</label><br>
    <textarea name="message" rows="5" required></textarea><br><br>

    <button type="submit">Send</button>
  </form>
</div>

<?php require_once "../includes/footer.php"; ?>
