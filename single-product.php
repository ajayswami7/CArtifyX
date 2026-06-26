<?php
require_once __DIR__ . '/includes/functions.php';
$product = get_product((int)($_GET['id'] ?? 0));
if (!$product) {
    $pageTitle = 'Product Not Found - CArtifyX';
    include __DIR__ . '/includes/header.php';
    echo '<section class="section-pad"><div class="container"><div class="empty-state">Product not found.</div></div></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    add_to_cart((int)$product['id'], (int)($_POST['quantity'] ?? 1), $_POST['size'] ?? '', $_POST['color'] ?? '');
    flash('success', 'Added to cart.');
    redirect('cart.php');
}
$pageTitle = $product['name'] . ' - CArtifyX';
include __DIR__ . '/includes/header.php';
$related = get_products(['category' => $product['category_slug'] ?? '', 'limit' => 4]);
$sizes = array_filter(array_map('trim', explode(',', $product['sizes'] ?? '')));
$colors = array_filter(array_map('trim', explode(',', $product['colors'] ?? '')));
?>
<section class="section-pad product-detail">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="gallery-main"><img id="mainProductImage" src="<?= e(product_image($product)) ?>" alt="<?= e($product['name']) ?>"></div>
                <div class="gallery-thumbs"><img src="<?= e(product_image($product)) ?>" alt=""></div>
            </div>
            <div class="col-lg-6">
                <span class="eyebrow"><?= e($product['category_name'] ?? 'Fashion') ?></span>
                <h1><?= e($product['name']) ?></h1>
                <p class="brand fs-5"><?= e($product['brand']) ?></p>
                <div class="price-row detail-price"><span class="price"><?= money_inr($product['price']) ?></span><span class="mrp"><?= money_inr($product['mrp']) ?></span><span class="offer"><?= product_discount($product) ?>% OFF</span></div>
                <p class="text-muted mt-3"><?= e($product['description']) ?></p>
                <form method="post" class="buy-box">
                    <label>Size</label>
                    <div class="choice-row"><?php foreach ($sizes as $i => $size): ?><input type="radio" class="btn-check" name="size" id="size<?= $i ?>" value="<?= e($size) ?>" <?= $i === 0 ? 'checked' : '' ?>><label class="choice" for="size<?= $i ?>"><?= e($size) ?></label><?php endforeach; ?></div>
                    <label>Color</label>
                    <div class="choice-row"><?php foreach ($colors as $i => $color): ?><input type="radio" class="btn-check" name="color" id="color<?= $i ?>" value="<?= e($color) ?>" <?= $i === 0 ? 'checked' : '' ?>><label class="choice" for="color<?= $i ?>"><?= e($color) ?></label><?php endforeach; ?></div>
                    <label>Quantity</label><input class="qty-input" type="number" name="quantity" min="1" value="1">
                    <div class="d-flex gap-3 flex-wrap mt-4">
                        <button class="lux-btn" type="submit"><i class="fa-solid fa-bag-shopping me-2"></i>Add to Cart</button>
                        <a class="btn-ghost" href="wishlist.php?action=add&id=<?= (int)$product['id'] ?>"><i class="fa-regular fa-heart me-2"></i>Wishlist</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="section-pad section-soft"><div class="container"><div class="section-title"><h2>Related Products</h2></div><div class="row g-4">
<?php foreach ($related as $item): if ((int)$item['id'] === (int)$product['id']) continue; ?><div class="col-6 col-lg-3"><?php render_product_card($item); ?></div><?php endforeach; ?>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
