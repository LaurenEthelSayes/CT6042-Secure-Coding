<?php
require_once "../includes/auth.php";
require_login();
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: upcycled.php");
  exit;
}

$listingId = (int)($_POST["listing_id"] ?? 0);
$buyerId = (int)($_SESSION["user_id"] ?? 0);

if ($listingId <= 0 || $buyerId <= 0) {
  header("Location: upcycled.php?err=bad");
  exit;
}

$stmt = $pdo->prepare("SELECT id, user_id, price FROM listings WHERE id = ?");
$stmt->execute([$listingId]);
$listing = $stmt->fetch();

if (!$listing) {
  header("Location: upcycled.php?err=missing");
  exit;
}

if ((int)$listing["user_id"] === $buyerId) {
  header("Location: upcycled.php?err=own");
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO listing_purchases (listing_id, buyer_user_id, purchase_price) VALUES (?, ?, ?)");
  $stmt->execute([$listingId, $buyerId, (float)$listing["price"]]);

  header("Location: upcycled.php?ok=bought&id=" . $listingId);
  exit;

} catch (Exception $e) {
  header("Location: upcycled.php?err=sold");
  exit;
}
