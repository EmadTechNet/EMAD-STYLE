<?php
session_start();
require_once 'config.php';

// Helper validators
function valid_email($email){
    return preg_match('/^[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.(com|net|org|edu|gov|info|me|co|[A-Za-z]{2})$/', $email);
}
function valid_password($pwd){
    // At least one lower, one upper, one digit, one special, min 8 chars
    return preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}/', $pwd);
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $captcha = trim($_POST['captcha'] ?? '');

    // CAPTCHA check
    if (!isset($_SESSION['captcha_text']) || strtolower($captcha) !== strtolower($_SESSION['captcha_text'])) {
        $errors[] = "Captcha غير صحيح. حاول مرة أخرى.";
    }

    if ($username === '') $errors[] = "الاسم مطلوب.";
    if (!valid_email($email)) $errors[] = "البريد الإلكتروني غير صالح.";
    if (!valid_password($password)) $errors[] = "كلمة المرور يجب أن تحتوي على حروف كبيرة وصغيرة وأرقام ورموز وطولها 8 أحرف على الأقل.";
    if ($password !== $confirm) $errors[] = "كلمتا المرور غير متطابقتين.";

    if (empty($errors)) {
        // Check duplicates
        $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM users WHERE username = :u OR email = :e");
        $stmt->execute([':u'=>$username, ':e'=>$email]);
        $row = $stmt->fetch();
        if ($row && $row['cnt'] > 0) {
            $errors[] = "اسم المستخدم أو البريد موجود بالفعل.";
        } else {
            // Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $now = (new DateTime())->format('Y-m-d H:i:s');
            $ins = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, created_at) VALUES (:u, :e, :p, :r, :c)");
            $ins->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash, ':r'=>'user', ':c'=>$now]);
            $success = "تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول.";
            // Optionally redirect to login
            // header("Location: login.php?registered=1"); exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="utf-8">
  <title>تسجيل حساب</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{direction:rtl;padding:20px}</style>
</head>
<body>
<div class="container col-md-6 offset-md-3">
  <h2 class="mt-4">إنشاء حساب</h2>

  <?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <?php if($success): ?>
    <div class="alert alert-success"><?=htmlspecialchars($success)?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="mb-3">
      <label for="username" class="form-label">اسم المستخدم</label>
      <input id="username" name="username" class="form-control" value="<?=htmlspecialchars($_POST['username'] ?? '')?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">البريد الإلكتروني</label>
      <input id="email" name="email" type="email" class="form-control" value="<?=htmlspecialchars($_POST['email'] ?? '')?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">كلمة المرور</label>
      <input id="password" name="password" type="password" class="form-control" required>
      <div class="form-text">يجب أن تحتوي على حروف كبيرة، حروف صغيرة، رقم ورمز، وطولها ≥ 8</div>
    </div>

    <div class="mb-3">
      <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
      <input id="confirm_password" name="confirm_password" type="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">كابتشا</label><br>
      <img src="captcha.php" alt="CAPTCHA Image"><br><br>
      <input id="captcha" name="captcha" class="form-control" placeholder="أدخل النص الموجود في الصورة" required>
    </div>

    <button class="btn btn-primary" type="submit">إنشاء حساب</button>
    <a href="login.php" class="btn btn-link">لدي حساب بالفعل</a>
  </form>
</div>

<script>
// Arrow navigation between inputs
const order = ['username','email','password','confirm_password','captcha'];
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
