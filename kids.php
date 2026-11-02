<?php
require "config.php";

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE category='kids'");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emad Store - Kids' Collection</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
<header>
    <nav>
        <a href="home.php" class="logo">Emad Store</a>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="men.php">Men</a>
            <a href="women.php">Women</a>
            <a href="kids.php" class="active">Kids</a>
            <a href="accessories.php">Accessories</a>
        </div>
        <a href="logout.php" class="logout-link" style="color: #a00e0e; text-decoration: none; font-size: 1rem; margin-left: 20px;">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <div class="nav-icons">
            <button class="search-btn">🔍</button>
            <button class="cart-btn">🛒</button>
        </div>
        <button class="menu-toggle">☰</button>
    </nav>
</header>
<main style="min-height: 60vh;">
    <h1>Kids' Collection</h1>
    <?php if (count($products) > 0): ?>
    <section class="product-grid">
        <?php foreach($products as $p): ?>
        <div class="product-card">
            <div class="product-image">
                <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                <?php if($p['discount'] > 0): ?>
                    <div class="discount-badge">-<?= $p['discount'] ?>%</div>
                <?php endif; ?>
            </div>
            <h3><?= $p['name'] ?></h3>
            <p class="price">
                $<?= number_format($p['price'] * (1 - $p['discount']/100), 2) ?>
                <?php if($p['discount'] > 0): ?>
                    <span class="original-price">$<?= $p['price'] ?></span>
                <?php endif; ?>
            </p>
            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <button type="submit" class="add-to-cart">Add to Cart</button>
            </form>
        </div>
        <?php endforeach; ?>
    </section>
    <?php else: ?>
    <div style="text-align: center; padding: 50px 20px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
        <h3 style="color: #666; margin-bottom: 10px;">No products available here yet</h3>
        <p style="color: #999;">We're working on adding amazing kids' products for you!</p>
    </div>
    <?php endif; ?>
</main>

<footer style="background-color: black; color: white; padding: 20px; text-align: center; margin-top: 50px;">
    <p>&copy; 2025 Emad Store. All rights reserved.</p>
</footer>
</body>
</html>
