<?php
session_start(); require 'config.php'; $cart=$_SESSION['cart']??[]; if(!$cart){header('Location: products.php');exit;}
$ids=implode(',',array_map('intval',array_keys($cart)));$r=$conn->query("SELECT * FROM products WHERE id IN ($ids) AND active=1");$total=0;$items=[];while($p=$r->fetch_assoc()){$p['quantity']=(int)$cart[$p['id']];$total+=$p['quantity']*$p['price'];$items[]=$p;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $name=trim($_POST['name']??'');$phone=trim($_POST['phone']??'');$email=trim($_POST['email']??'');$address=trim($_POST['address']??'');
 if($name&&$phone&&$address){
   $stmt=$conn->prepare("INSERT INTO orders(customer_name,phone,email,address,total,status) VALUES(?,?,?,?,?,'Pending')");
   $stmt->bind_param("ssssd",$name,$phone,$email,$address,$total);$stmt->execute();$orderId=$conn->insert_id;
   $it=$conn->prepare("INSERT INTO order_items(order_id,product_id,quantity,price) VALUES(?,?,?,?)");
   foreach($items as $p){$it->bind_param("iiid",$orderId,$p['id'],$p['quantity'],$p['price']);$it->execute();}
   $_SESSION['cart']=[];header("Location: order_success.php?id=".$orderId);exit;
 }
 $error='Please fill in your name, phone number and delivery address.';
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Checkout | MAX ELECTRONICS HUB</title><link rel="stylesheet" href="style.css"></head><body><?php include 'header.php'; ?><main class="page container"><h1>Checkout</h1><?php if(!empty($error)): ?><div class="notice danger"><?=htmlspecialchars($error)?></div><?php endif; ?><form class="checkout-form" method="post"><p><b>Order total: TZS <?=number_format($total)?></b></p><label>Full Name<input name="name" required></label><label>Phone Number<input name="phone" required placeholder="07XXXXXXXX"></label><label>Email (optional)<input type="email" name="email"></label><label>Delivery Address<textarea name="address" rows="4" required></textarea></label><br><button class="btn primary" type="submit">Place Order</button></form></main><?php include 'footer.php'; ?></body></html>