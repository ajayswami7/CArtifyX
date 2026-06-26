<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && db()) {
    $stmt = db()->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
    $stmt->execute([$_POST['name'], $_POST['phone'], $_SESSION['user_id']]);
    flash('success', 'Profile updated.');
    redirect('profile.php');
}
$pageTitle = 'Profile - CArtifyX';
include __DIR__ . '/includes/header.php';
$user = current_user();
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Account</span><h1>Profile</h1></div></section>
<section class="section-pad"><div class="container"><div class="row g-4"><div class="col-lg-4"><div class="profile-card"><i class="fa-regular fa-user"></i><h3><?= e($user['name'] ?? 'Customer') ?></h3><p><?= e($user['email'] ?? '') ?></p><a href="orders.php">Order history</a><a href="wishlist.php">Wishlist</a><a href="logout.php">Logout</a></div></div><div class="col-lg-8"><div class="form-panel"><h3>Edit Profile</h3><form method="post"><input name="name" value="<?= e($user['name'] ?? '') ?>" required><input name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="Phone"><button class="lux-btn">Save Changes</button></form></div></div></div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
