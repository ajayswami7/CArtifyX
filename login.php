<?php
include __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (db()) {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        $valid = $user && (password_verify($password, $user['password']) || hash_equals($user['password'], hash('sha256', $password)));
        if ($valid) {
            $_SESSION['user_id'] = $user['id'];
            flash('success', 'Welcome back to CArtifyX.');
            redirect('profile.php');
        }
    }
    flash('danger', 'Invalid email or password.');
}
$pageTitle = 'Login - CArtifyX';
include __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">Account</span><h1>Login</h1><form method="post">
<input type="email" name="email" placeholder="Email address" required>
<input type="password" name="password" placeholder="Password" required>
<button class="lux-btn w-100">Login</button>
<a href="#" class="small-link">Forgot password?</a><p>New here? <a href="signup.php">Create an account</a></p>
</form></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
