<?php
session_start();
require_once 'config.php';
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
}
if (isset($_COOKIE['rememberme'])) {
    // remove from DB
    $hash = hash('sha256', $_COOKIE['rememberme']);
    $stmt = $pdo->prepare("DELETE FROM persistent_logins WHERE token_hash = :h");
    $stmt->execute([':h'=>$hash]);
    setcookie('rememberme', '', time()-3600, '/', '', isset($_SERVER['HTTPS']), true);
}
session_destroy();
header("Location: login.php");
exit;
?>