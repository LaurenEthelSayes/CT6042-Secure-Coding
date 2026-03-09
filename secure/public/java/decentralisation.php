<?php
require_once "../../includes/auth.php";
require_login();

$out = "";
$root = realpath(__DIR__ . "/../../");
$bin = $root . DIRECTORY_SEPARATOR . "java" . DIRECTORY_SEPARATOR . "bin";
$trustedFile = $root . DIRECTORY_SEPARATOR . "java" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "Cust.ser";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $cmd = "java -cp " . escapeshellarg($bin) . " CustomerAppServer " . escapeshellarg($trustedFile);
  $out = (string)@shell_exec($cmd);
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

      <form method="post">
        <button type="submit">Deserialise Cust.ser</button>
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