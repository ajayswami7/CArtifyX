<?php
require_once __DIR__ . '/includes/functions.php';
require_login();
$items = get_cart_items();
if (!$items) {
    flash('warning', 'Add products before checkout.');
    redirect('products.php');
}
$totals = cart_totals($items);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && db()) {
    db()->beginTransaction();
    try {
        $stmt = db()->prepare('INSERT INTO addresses (user_id, full_name, phone, address_line, city, state, pincode) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $_POST['full_name'], $_POST['phone'], $_POST['address_line'], $_POST['city'], $_POST['state'], $_POST['pincode']]);
        $addressId = db()->lastInsertId();
        $order = db()->prepare('INSERT INTO orders (user_id, address_id, subtotal, gst, shipping, total, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, "Placed")');
        $order->execute([$_SESSION['user_id'], $addressId, $totals['subtotal'], $totals['gst'], $totals['shipping'], $totals['total'], $_POST['payment_method']]);
        $orderId = db()->lastInsertId();
        $itemStmt = db()->prepare('INSERT INTO order_items (order_id, product_id, quantity, price, size, color) VALUES (?, ?, ?, ?, ?, ?)');
        foreach ($items as $item) {
            $itemStmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price'], $item['size'] ?? '', $item['color'] ?? '']);
        }
        $pay = db()->prepare('INSERT INTO payments (order_id, amount, method, status) VALUES (?, ?, ?, "Pending")');
        $pay->execute([$orderId, $totals['total'], $_POST['payment_method']]);
        $clear = db()->prepare('DELETE FROM cart WHERE user_id = ?');
        $clear->execute([$_SESSION['user_id']]);
        db()->commit();
        flash('success', 'Order placed successfully.');
        redirect('orders.php');
    } catch (Throwable $e) {
        db()->rollBack();
        flash('danger', 'Could not place order.');
    }
}
$pageTitle = 'Checkout - CArtifyX';
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Checkout</span><h1>Delivery & Payment</h1></div></section>
<section class="section-pad"><div class="container"><form method="post" class="row g-4">
<div class="col-lg-8"><div class="form-panel"><h3>Shipping Address</h3><div class="row g-3">
<div class="col-md-6"><input name="full_name" placeholder="Full name" required></div><div class="col-md-6"><input name="phone" placeholder="Phone" required></div><div class="col-12"><input name="address_line" placeholder="Address line" required></div><div class="col-md-4"><input name="city" placeholder="City" required></div><div class="col-md-4"><input name="state" placeholder="State" required></div><div class="col-md-4"><input name="pincode" placeholder="Pincode" required></div>
</div><h3 class="mt-4">Payment Method</h3><select name="payment_method" required><option>Cash on Delivery</option><option>UPI</option><option>Credit Card</option><option>Net Banking</option></select></div></div>
<div class="col-lg-4"><div class="summary-card"><h3>Order Summary</h3><p><span>Subtotal</span><strong><?= money_inr($totals['subtotal']) ?></strong></p><p><span>GST</span><strong><?= money_inr($totals['gst']) ?></strong></p><p><span>Shipping</span><strong><?= $totals['shipping'] ? money_inr($totals['shipping']) : 'Free' ?></strong></p><hr><p class="total"><span>Total</span><strong><?= money_inr($totals['total']) ?></strong></p><button class="lux-btn w-100 justify-content-center">Place Order</button></div></div>
</form></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
