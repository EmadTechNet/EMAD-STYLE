<?php
// Test cart system
session_start();
require "config.php";

echo "<h1>Cart System Test</h1>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>❌ No user logged in. Please <a href='login.php'>login</a> first.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
echo "<p style='color: green;'>✅ User logged in: " . $_SESSION['username'] . " (ID: $user_id)</p>";

// Test cart table structure
try {
    $stmt = $pdo->query("DESCRIBE cart");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Cart Table Structure:</h2>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>" . $column['Field'] . " - " . $column['Type'] . "</li>";
    }
    echo "</ul>";
    
    // Check if user_id column exists
    $has_user_id = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'user_id') {
            $has_user_id = true;
            break;
        }
    }
    
    if ($has_user_id) {
        echo "<p style='color: green;'>✅ Cart table has user_id column</p>";
    } else {
        echo "<p style='color: red;'>❌ Cart table missing user_id column. Run update_cart_table.sql</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error checking cart table: " . $e->getMessage() . "</p>";
}

// Test user's cart items
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.quantity, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $cartItems = $stmt->fetchAll();
    
    echo "<h2>Your Cart Items:</h2>";
    if (count($cartItems) > 0) {
        echo "<ul>";
        foreach ($cartItems as $item) {
            echo "<li>" . $item['name'] . " - Qty: " . $item['quantity'] . " - $" . $item['price'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error checking cart items: " . $e->getMessage() . "</p>";
}

// Test all cart items (for debugging)
try {
    $stmt = $pdo->query("
        SELECT c.id, c.user_id, c.quantity, p.name 
        FROM cart c 
        JOIN products p ON c.product_id = p.id
    ");
    $allCartItems = $stmt->fetchAll();
    
    echo "<h2>All Cart Items (Debug):</h2>";
    if (count($allCartItems) > 0) {
        echo "<ul>";
        foreach ($allCartItems as $item) {
            echo "<li>User ID: " . $item['user_id'] . " - " . $item['name'] . " - Qty: " . $item['quantity'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No items in any cart.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error checking all cart items: " . $e->getMessage() . "</p>";
}

echo "<br><a href='home.php'>Go to Home</a> | <a href='cart.php'>View Cart</a> | <a href='logout.php'>Logout</a>";
?>
