<?php
include __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$name || !$email || strlen($password) < 6) {
        flash('danger', 'Please enter valid details. Password must be at least 6 characters.');
    } elseif (db()) {
        try {
            $stmt = db()->prepare('INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
            $_SESSION['user_id'] = (int)db()->lastInsertId();
            flash('success', 'Your CArtifyX account is ready.');
            redirect('profile.php');
        } catch (PDOException $e) {
            flash('danger', 'This email is already registered.');
        }
    } else {
        flash('warning', 'Database connection is required for signup.');
    }
}
$pageTitle = 'Signup - CArtifyX';
include __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">Join</span><h1>Create account</h1><form method="post">
<input name="name" placeholder="Full name" required><input type="email" name="email" placeholder="Email address" required><input name="phone" placeholder="Phone number"><input type="password" name="password" placeholder="Password" required>
<button class="lux-btn w-100">Create Account</button><p>Already have an account? <a href="login.php">Login</a></p>
</form></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
