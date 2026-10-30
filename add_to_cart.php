<?php
session_start();
require 'config.php';
$id=(int)($_POST['product_id']??0); $qty=max(1,min(20,(int)($_POST['quantity']??1)));
$stmt=$conn->prepare("SELECT id FROM products WHERE id=? AND active=1"); $stmt->bind_param("i",$id); $stmt->execute();
if($stmt->get_result()->num_rows){$_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+$qty;}
header('Location: cart.php'); exit;
?>