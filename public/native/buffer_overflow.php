<?php
require_once "../../includes/auth.php";
require_login();
require_once "../../includes/native_bridge.php";

$out = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $res = native_overflow_run($_POST["input"] ?? "");
  $out = $res["out"] ?? "";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Buffer Overflow</title>
  <link rel="stylesheet" href="/CT6042-Secure-Coding/assets/styles.css">
</head>
<body>
  <main class="container">
    <div class="card">
      <h1>Buffer Overflow</h1>
      <p>Enter your name:</p>

      <form method="post">
        <label>Name</label><br>
        <textarea name="input" rows="3" style="width:100%;" required placeholder="e.g., Molly"></textarea><br><br>
        <button type="submit">Send</button>
      </form>

      <?php if ($out !== ""): ?>
        <hr>
        <p><strong>Output:</strong></p>
        <pre><?php echo htmlspecialchars($out, ENT_QUOTES, "UTF-8"); ?></pre>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>