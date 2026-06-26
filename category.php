<?php
require_once __DIR__ . '/includes/functions.php';
$slug = trim($_GET['slug'] ?? '');
$category = get_category_by_slug($slug);
$pageTitle = ($category['name'] ?? 'Category') . ' - CArtifyX';
include __DIR__ . '/includes/header.php';
$products = get_products(['category' => $slug, 'sort' => $_GET['sort'] ?? 'newest']);
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Category</span><h1><?= e($category['name'] ?? 'Fashion Edit') ?></h1><p>Fresh CArtifyX picks, sorted for effortless discovery.</p></div></section>
<section class="section-pad"><div class="container"><div class="row g-4">
<?php foreach ($products as $product): ?><div class="col-6 col-lg-3"><?php render_product_card($product); ?></div><?php endforeach; ?>
<?php if (!$products): ?><div class="empty-state">No products in this category yet.</div><?php endif; ?>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
