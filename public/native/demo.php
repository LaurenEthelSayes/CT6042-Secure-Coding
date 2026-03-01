<?php
require_once "../../includes/auth.php";
require_login();
require_once "../../includes/header.php";
require_once "../../includes/native_bridge.php";

$out = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $out = native_echo($_POST["input"] ?? "");
}
?>

<div class="card">
  <h1>Native Component Demo</h1>
  <p>Integration seam for the native module (lab).</p>

  <form method="post">
    <label>Input</label><br>
    <input type="text" name="input" style="width:100%;" required><br><br>
    <button type="submit">Send to native module</button>
  </form>

  <?php if ($out !== ""): ?>
    <hr>
    <p><strong>Native output:</strong></p>
    <pre><?php echo htmlspecialchars($out, ENT_QUOTES, "UTF-8"); ?></pre>
  <?php endif; ?>
</div>

<?php require_once "../../includes/footer.php"; ?>
