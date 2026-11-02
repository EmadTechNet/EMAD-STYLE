<?php
require "config.php";

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get all products from all categories
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emad Store - Home</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
<header>
    <nav>
        <a href="home.php" class="logo">Emad Store</a>
        <div class="nav-links">
            <a href="home.php" class="active">Home</a>
            <a href="men.php">Men</a>
            <a href="women.php">Women</a>
            <a href="kids.php">Kids</a>
            <a href="accessories.php">Accessories</a>
        </div>
        <a href="logout.php" class="logout-link" style="color: #a00e0e; text-decoration: none;
    font-size: 1rem;
    margin-left: 20px;">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <div class="nav-icons">
            <button class="search-btn">🔍</button>
            <a href="cart.php"><button class="cart-btn">🛒</button></a>
        </div>
        <button class="menu-toggle">☰</button>
    </nav>
</header>

<main style="min-height: 60vh;">
    <section class="hero">
        <img src="images/Home/5.jpg" alt="" width="100%">
        <h1>Summer Collection 2025</h1>
        <p>Up to 50% off on selected items</p>
        <button class="shop-now">Shop Now</button>
    </section>

    <section class="featured-products">
        <h2>All Products</h2>
        <?php if (count($allProducts) > 0): ?>
        <div class="product-grid">
            <?php foreach($allProducts as $p): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                    <?php if($p['discount'] > 0): ?>
                        <div class="discount-badge">-<?= $p['discount'] ?>%</div>
                    <?php endif; ?>
                </div>
                <h3><?= $p['name'] ?></h3>
                <p class="category-tag" style="background: #f0f0f0; padding: 2px 8px; border-radius: 12px; font-size: 12px; color: #666; display: inline-block; margin-bottom: 8px;">
                    <?= ucfirst($p['category']) ?>
                </p>
                <p class="price">
                    $<?= number_format($p['price'] * (1 - $p['discount']/100), 2) ?>
                    <?php if($p['discount'] > 0): ?>
                        <span class="original-price">$<?= $p['price'] ?></span>
                    <?php endif; ?>
                </p>
                <!-- Add to cart form -->
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 50px 20px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #666; margin-bottom: 10px;">No products available here yet</h3>
            <p style="color: #999;">We're working on adding amazing products for you!</p>
        </div>
        <?php endif; ?>
    </section>
</main>

<footer style="background-color: black; color: white; padding: 20px; text-align: center; margin-top: 50px;">
    <p>&copy; 2025 Emad Store. All rights reserved.</p>
</footer>
</body>
</html>
