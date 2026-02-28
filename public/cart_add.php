<?php
require_once "../includes/auth.php";
require_login();

if (!isset($_SESSION["cart"])) { $_SESSION["cart"] = []; }

$id = (int)($_POST["id"] ?? 0);
$qty = (int)($_POST["qty"] ?? 1);
if ($qty < 1) { $qty = 1; }

if ($id > 0) {
  $_SESSION["cart"][$id] = ($_SESSION["cart"][$id] ?? 0) + $qty;
}

header("Location: shop.php");
exit;
