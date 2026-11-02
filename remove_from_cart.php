<?php
require "config.php";

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cart_id"])) {
    $cart_id = intval($_POST["cart_id"]);
    
    try {
        // Only delete cart item if it belongs to the current user
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :cid AND user_id = :uid");
        $stmt->execute(['cid' => $cart_id, 'uid' => $user_id]);
        
        // Redirect back to cart
        header("Location: cart.php");
        exit;
        
    } catch (PDOException $e) {
        // Log error and redirect with error message
        error_log("Remove from cart error: " . $e->getMessage());
        header("Location: cart.php?error=1");
        exit;
    }
} else {
    // Invalid request
    header("Location: cart.php");
    exit;
}
?>
