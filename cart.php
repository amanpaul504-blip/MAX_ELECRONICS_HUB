<?php
session_start(); require 'config.php';
$cart=$_SESSION['cart']??[]; $items=[]; $total=0;
if($cart){$ids=implode(',',array_map('intval',array_keys($cart))); $r=$conn->query("SELECT * FROM products WHERE id IN ($ids) AND active=1"); while($p=$r->fetch_assoc()){ $q=(int)$cart[$p['id']]; $p['quantity']=$q; $p['subtotal']=$q*$p['price']; $total+=$p['subtotal']; $items[]=$p; }}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Cart | MAX ELECTRONICS HUB</title><link rel="stylesheet" href="style.css"></head><body><?php include 'header.php'; ?><main class="page container"><h1>Shopping Cart</h1>
<?php if(!$items): ?><div class="notice">Your cart is empty. <a href="products.php"><b>Browse products</b></a>.</div>
<?php else: ?><form action="update_cart.php" method="post"><table class="cart-table"><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
<?php foreach($items as $p): ?><tr><td><?=htmlspecialchars($p['name'])?></td><td>TZS <?=number_format($p['price'])?></td><td><input class="qty" type="number" min="0" max="20" name="qty[<?=$p['id']?>]" value="<?=$p['quantity']?>"></td><td>TZS <?=number_format($p['subtotal'])?></td></tr><?php endforeach; ?>
<tr><th colspan="3">Total</th><th>TZS <?=number_format($total)?></th></tr></table><br><button class="btn secondary" type="submit">Update Cart</button> <a class="btn primary" href="checkout.php">Checkout</a></form><?php endif; ?></main><?php include 'footer.php'; ?></body></html>