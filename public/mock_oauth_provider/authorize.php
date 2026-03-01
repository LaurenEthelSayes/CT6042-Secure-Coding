<?php
// Mock OAuth provider (local)
$redirect = $_GET["redirect_uri"] ?? "";
$state = $_GET["state"] ?? "";

// Simple mock "consent" UX
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>CatBook Consent</title></head>
<body style="font-family:Arial; padding:30px;">
  <h1>CatBook OAuth (Mock)</h1>
  <p>This is a local mock provider for your CT6042 lab.</p>
  <p>Click continue to send an auth code back to Cats@Home.</p>

  <form method="post">
    <label>Mock user</label><br>
    <select name="user">
      <option value="molly">molly</option>
      <option value="admin">admin</option>
      <option value="test">test</option>
    </select>
    <br><br>
    <button type="submit">Continue</button>
  </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user = $_POST["user"] ?? "molly";
  $code = bin2hex(random_bytes(8));

  // Store a mapping code -> user (file-based, local only)
  $store = __DIR__ . "/codes.json";
  $data = file_exists($store) ? json_decode(file_get_contents($store), true) : [];
  $data[$code] = $user;
  file_put_contents($store, json_encode($data));

  $sep = (str_contains($redirect, "?")) ? "&" : "?";
  header("Location: " . $redirect . $sep . "code=" . urlencode($code) . "&state=" . urlencode($state));
  exit;
}
