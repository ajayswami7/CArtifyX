<?php
require_once __DIR__ . '/includes/functions.php';
$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);
if ($id && $action === 'add') {
    add_to_wishlist($id);
    flash('success', 'Added to wishlist.');
    redirect('wishlist.php');
}
if ($id && $action === 'remove') {
    remove_from_wishlist($id);
    flash('success', 'Removed from wishlist.');
    redirect('wishlist.php');
}
$pageTitle = 'Wishlist - CArtifyX';
include __DIR__ . '/includes/header.php';
$items = get_wishlist_items();
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Saved</span><h1>Wishlist</h1></div></section>
<section class="section-pad"><div class="container"><div class="row g-4">
<?php foreach ($items as $product): ?><div class="col-6 col-lg-3"><?php render_product_card($product); ?><a class="remove-link" href="wishlist.php?action=remove&id=<?= (int)$product['id'] ?>">Remove</a></div><?php endforeach; ?>
<?php if (!$items): ?><div class="empty-state">Your wishlist is empty.</div><?php endif; ?>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
