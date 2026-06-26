<?php
require_once __DIR__ . '/../includes/functions.php';
if (is_admin_logged_in()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (db()) {
        $stmt = db()->prepare('SELECT * FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        $valid = $admin && (password_verify($password, $admin['password']) || hash_equals($admin['password'], hash('sha256', $password)));
        if ($valid) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Invalid admin credentials.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login - CArtifyX</title><link href="../assets/css/admin.css" rel="stylesheet"></head><body><section class="login-screen"><div class="login-card"><h1>CArtifyX Admin</h1><p></p><?php if($error): ?><p style="color:#c82135"><?= e($error) ?></p><?php endif; ?><form class="admin-form" method="post"><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Password" required><button class="admin-btn pink">Login</button></form></div></section></body></html>
