<?php
session_start();
require 'config.php';
$category = $_GET['category'] ?? '';
$categories = ['Chargers','Cables','Audio','Power','Phone Accessories'];
if ($category && in_array($category,$categories,true)) {
    $stmt=$conn->prepare("SELECT * FROM products WHERE category=? AND active=1 ORDER BY id DESC");
    $stmt->bind_param("s",$category); $stmt->execute(); $result=$stmt->get_result();
} else {
    $result=$conn->query("SELECT * FROM products WHERE active=1 ORDER BY id DESC");
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Products | MAX ELECTRONICS HUB</title><link rel="stylesheet" href="style.css"></head><body>
<?php include 'header.php'; ?><main class="page container">
<span class="badge">ONLINE STORE</span><h1>Electronics Accessories</h1><p class="muted">Choose an accessory and add it to your cart.</p>
<div class="filters"><a class="btn secondary" href="products.php">All</a><?php foreach($categories as $c): ?><a class="btn secondary" href="?category=<?=urlencode($c)?>"><?=htmlspecialchars($c)?></a><?php endforeach; ?></div>
<div class="products-grid">
<?php while($p=$result->fetch_assoc()): ?>
<article class="product-card"><div class="product-image"><?=htmlspecialchars($p['icon'])?></div><div class="product-body">
<h3><?=htmlspecialchars($p['name'])?></h3><p class="muted"><?=htmlspecialchars($p['description'])?></p>
<div class="price">TZS <?=number_format($p['price'])?></div>
<form action="add_to_cart.php" method="post"><input type="hidden" name="product_id" value="<?=$p['id']?>"><input class="qty" type="number" name="quantity" value="1" min="1" max="20"><button class="btn primary" type="submit">Add to Cart</button></form>
</div></article>
<?php endwhile; ?>
</div></main><?php include 'footer.php'; ?></body></html>