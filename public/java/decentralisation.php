<?php
require_once "../../includes/auth.php";
require_login();
require_once "../../includes/java_bridge.php";

$out = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $out = java_decentralisation_run($_POST["payload"] ?? "");
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Java Decentralisation</title>
  <link rel="stylesheet" href="/CT6042-Secure-Coding/assets/styles.css">
</head>
<body>
  <main class="container">
    <div class="card">
      <h1>Java Decentralisation</h1>
      <p>Dedicated lab area for the Java vulnerability component.</p>

      <form method="post">
        <label>Payload</label><br>
        <textarea name="payload" rows="6" style="width:100%;" required></textarea><br><br>
        <button type="submit">Send to Java component</button>
      </form>

      <?php if ($out !== ""): ?>
        <hr>
        <p><strong>Java output:</strong></p>
        <pre><?php echo htmlspecialchars($out, ENT_QUOTES, "UTF-8"); ?></pre>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
