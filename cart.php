<?php
require "config.php";

    // Start session and check if user is logged in
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Get cart items for the current user only
    $stmt = $pdo->prepare("
        SELECT c.id, c.quantity, p.name, p.price, p.discount, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
$stmt->execute(['user_id' => $user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);    //إذا كنت تبغى كل المنتجات اللي في العربة:

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $price = $item['price'] * (1 - $item['discount']/100);
    $total += $price * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Emad Store</title>
    <link rel="stylesheet" href="styles/home.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        main {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <a href="home.php" class="logo">Emad Store</a>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="men.php">Men</a>
            <a href="women.php">Women</a>
            <a href="kids.php">Kids</a>
            <a href="accessories.php">Accessories</a>
        </div>
        <a href="logout.php" class="logout-link" style="color: #a00e0e; text-decoration: none; font-size: 1rem; margin-left: 20px;">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <div class="nav-icons">
            <a href="cart.php"><button class="cart-btn">🛒</button></a>
        </div>
        <button class="menu-toggle">☰</button>
    </nav>
</header>

<main>
    <h1>Your Cart (<?= htmlspecialchars($_SESSION['username']) ?>)</h1>
    <?php if (count($cartItems) > 0): ?>
        <div class="product-grid">
            <?php foreach($cartItems as $item): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                </div>
                <h3><?= $item['name'] ?></h3>
                <p class="price">
                    $<?= number_format($item['price'] * (1 - $item['discount']/100), 2) ?>
                    <?php if($item['discount'] > 0): ?>
                        <span class="original-price">$<?= $item['price'] ?></span>
                    <?php endif; ?>
                </p>
                <p>Quantity: <?= $item['quantity'] ?></p>
                <p>Subtotal: $<?= number_format($item['price'] * (1 - $item['discount']/100) * $item['quantity'], 2) ?></p>
                <form method="post" action="remove_from_cart.php">
                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                    <button type="submit" style="background: #ff4444; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Remove</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
            <h2>Total: $<?= number_format($total, 2) ?></h2>
            <button style="background: #4CAF50; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; font-size: 16px;">Checkout</button>
        </div>
    <?php else: ?>
        <div style="text-align: center; margin-top: 50px;">
            <h2>Your cart is empty.</h2>
            <p>Add some products to your cart!</p>
            <a href="home.php" style="background: #4CAF50; color: white; text-decoration: none; padding: 12px 24px; border-radius: 4px; display: inline-block; margin-top: 20px;">Continue Shopping</a>
        </div>
    <?php endif; ?>
</main>

<footer style="background-color: black; color: white; padding: 20px; text-align: center; margin-top: 50px;">
    <p>&copy; 2024 Emad Store. All rights reserved.</p>
</footer>
</body>
</html>
