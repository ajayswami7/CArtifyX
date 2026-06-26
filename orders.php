<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
$pageTitle = 'Orders - CArtifyX';
include __DIR__ . '/includes/header.php';
$orders = [];
if (db()) {
    $stmt = db()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
}
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">History</span><h1>Your Orders</h1></div></section>
<section class="section-pad"><div class="container">
<?php foreach ($orders as $order): ?><div class="order-card"><div><strong>Order #<?= (int)$order['id'] ?></strong><p><?= e($order['status']) ?> | <?= e($order['created_at']) ?></p></div><strong><?= money_inr($order['total']) ?></strong></div><?php endforeach; ?>
<?php if (!$orders): ?><div class="empty-state">No orders yet.</div><?php endif; ?>
</div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
