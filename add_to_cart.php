<?php
require "config.php";

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    $product_id = intval($_POST["product_id"]);

    try {
        // Check if product already in this user's cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE product_id = :pid AND user_id = :uid");
        $stmt->execute(['pid' => $product_id, 'uid' => $user_id]);

        if ($stmt->rowCount() > 0) {
            // Update quantity if product already exists
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_id = :pid AND user_id = :uid");
            $stmt->execute(['pid' => $product_id, 'uid' => $user_id]);
        } else {
            // Add new product to cart
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:uid, :pid, 1)");
            $stmt->execute(['uid' => $user_id, 'pid' => $product_id]);
        }
        
        // Redirect back to the previous page or cart
        $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'cart.php';
        header("Location: " . $redirect_url);
        exit;
        
    } catch (PDOException $e) {
        // Log error and redirect with error message
        error_log("Add to cart error: " . $e->getMessage());
        header("Location: cart.php?error=1");
        exit;
    }
} else {
    // Invalid request
    header("Location: home.php");
    exit;
}
?>
