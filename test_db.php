<?php
// Simple database test file
echo "<h1>Database Connection Test</h1>";

try {
    require "config.php";
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if users table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<p style='color: green;'>✅ Users table exists with " . $result['count'] . " records</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Users table error: " . $e->getMessage() . "</p>";
    }
    
    // Test if products table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $result = $stmt->fetch();
        echo "<p style='color: green;'>✅ Products table exists with " . $result['count'] . " records</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Products table error: " . $e->getMessage() . "</p>";
    }
    
    // Test Database helper class
    try {
        $userCount = $db->count('users');
        echo "<p style='color: green;'>✅ Database helper class working! Users count: $userCount</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ Database helper error: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
}

echo "<br><a href='login.php'>Go to Login</a>";
?>
