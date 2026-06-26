<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        add_to_cart((int)$_POST['product_id'], (int)($_POST['quantity'] ?? 1), $_POST['size'] ?? '', $_POST['color'] ?? '');
        if (!empty($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'count' => cart_count()]);
            exit;
        }
        flash('success', 'Added to cart.');
    }
    if ($action === 'update') {
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        if (is_logged_in() && db()) {
            $stmt = db()->prepare('UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$qty, $_POST['cart_id'], $_SESSION['user_id']]);
        } elseif (isset($_SESSION['cart'][$_POST['cart_id']])) {
            $_SESSION['cart'][$_POST['cart_id']]['quantity'] = $qty;
        }
    }
    if ($action === 'remove') {
        if (is_logged_in() && db()) {
            $stmt = db()->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
            $stmt->execute([$_POST['cart_id'], $_SESSION['user_id']]);
        } else {
            unset($_SESSION['cart'][$_POST['cart_id']]);
        }
        flash('success', 'Item removed from cart.');
    }
    redirect('cart.php');
}
$pageTitle = 'Cart - CArtifyX';
include __DIR__ . '/includes/header.php';
$items = get_cart_items();
$totals = cart_totals($items);
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Shopping bag</span><h1>Your Cart</h1></div></section>
<section class="section-pad"><div class="container"><div class="row g-4">
<div class="col-lg-8">
<?php if (!$items): ?><div class="empty-state">Your cart is waiting for a style upgrade.</div><?php endif; ?>
<?php foreach ($items as $item): ?><div class="cart-line">
<img src="<?= e(product_image($item)) ?>" alt="<?= e($item['name']) ?>"><div class="flex-grow-1"><h3><?= e($item['name']) ?></h3><p><?= e($item['brand']) ?> <?= !empty($item['size']) ? '| Size ' . e($item['size']) : '' ?> <?= !empty($item['color']) ? '| ' . e($item['color']) : '' ?></p><strong><?= money_inr($item['price']) ?></strong></div>
<form method="post" class="qty-form"><input type="hidden" name="action" value="update"><input type="hidden" name="cart_id" value="<?= e($item['id']) ?>"><input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="1"><button>Update</button></form>
<form method="post"><input type="hidden" name="action" value="remove"><input type="hidden" name="cart_id" value="<?= e($item['id']) ?>"><button class="icon-danger"><i class="fa-regular fa-trash-can"></i></button></form>
</div><?php endforeach; ?>
</div>
<div class="col-lg-4"><div class="summary-card"><h3>Order Summary</h3><p><span>Subtotal</span><strong><?= money_inr($totals['subtotal']) ?></strong></p><p><span>GST 18%</span><strong><?= money_inr($totals['gst']) ?></strong></p><p><span>Shipping</span><strong><?= $totals['shipping'] ? money_inr($totals['shipping']) : 'Free' ?></strong></p><hr><p class="total"><span>Total</span><strong><?= money_inr($totals['total']) ?></strong></p><a class="lux-btn w-100 justify-content-center" href="checkout.php">Checkout</a></div></div>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
