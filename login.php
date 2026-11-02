<?php
session_start();
require_once 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($email === '' || $password === '') {
        $errors[] = "البريد وكلمة المرور مطلوبة.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :e OR username = :e LIMIT 1");
        $stmt->execute([':e'=>$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            // login ok
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // update last login
            $upd = $pdo->prepare("UPDATE users SET last_login_at = :l WHERE id = :id");
            $upd->execute([':l'=>date('Y-m-d H:i:s'), ':id'=>$user['id']]);

            if ($remember) {
                // create remember token
                $token = bin2hex(random_bytes(32));
                $hash = hash('sha256', $token);
                $expires = date('Y-m-d H:i:s', time() + 60*60*24*30); // 30 days
                $ins = $pdo->prepare("INSERT INTO persistent_logins (user_id, token_hash, expires_at, created_at) VALUES (:uid, :th, :ex, :cr)");
                $ins->execute([':uid'=>$user['id'], ':th'=>$hash, ':ex'=>$expires, ':cr'=>date('Y-m-d H:i:s')]);
                setcookie('rememberme', $token, time()+60*60*24*30, '/', '', isset($_SERVER['HTTPS']), true);
            }

            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "بيانات الدخول غير صحيحة.";
        }
    }
} else {
    // If not posting, check remember me cookie
    if (!isset($_SESSION['user_id']) && isset($_COOKIE['rememberme'])) {
        $token = $_COOKIE['rememberme'];
        $hash = hash('sha256', $token);
        $stmt = $pdo->prepare("SELECT pl.user_id, u.* FROM persistent_logins pl JOIN users u ON u.id = pl.user_id WHERE pl.token_hash = :h AND pl.expires_at > NOW() LIMIT 1");
        $stmt->execute([':h'=>$hash]);
        $row = $stmt->fetch();
        if ($row) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            // invalid token -> clear cookie
            setcookie('rememberme', '', time()-3600, '/', '', isset($_SERVER['HTTPS']), true);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="utf-8">
  <title>تسجيل الدخول</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{direction:rtl;padding:20px}</style>
</head>
<body>
<div class="container col-md-6 offset-md-3">
  <h2 class="mt-4">تسجيل الدخول</h2>

  <?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label for="email" class="form-label">البريد أو اسم المستخدم</label>
      <input id="email" name="email" type="text" class="form-control" value="<?=htmlspecialchars($_POST['email'] ?? '')?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">كلمة المرور</label>
      <input id="password" name="password" type="password" class="form-control" required>
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="remember" name="remember">
      <label class="form-check-label" for="remember">تذكّرني</label>
    </div>

    <button class="btn btn-primary" type="submit">دخول</button>
    <a href="register.php" class="btn btn-link">إنشاء حساب جديد</a>
  </form>
</div>

<script>
// Arrow navigation
const order = ['email','password'];
document.addEventListener('keydown', function(e){
  if(['ArrowDown','ArrowUp'].includes(e.key)){
    const active = document.activeElement;
    const idx = order.indexOf(active.id);
    if(idx !== -1){
      if(e.key === 'ArrowDown' && idx < order.length-1){
        document.getElementById(order[idx+1]).focus();
      } else if(e.key === 'ArrowUp' && idx > 0){
        document.getElementById(order[idx-1]).focus();
      }
      e.preventDefault();
    }
  }
});
</script>

</body>
</html>
