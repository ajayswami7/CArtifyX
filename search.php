<?php
$q = trim($_GET['q'] ?? '');
$pageTitle = 'Search - CArtifyX';
include __DIR__ . '/includes/header.php';
$products = get_products(['q' => $q]);
?>
<section class="page-hero compact"><div class="container"><span class="eyebrow">Search</span><h1><?= $q ? 'Results for "' . e($q) . '"' : 'Search CArtifyX' ?></h1></div></section>
<section class="section-pad"><div class="container"><div class="row g-4">
<?php foreach ($products as $product): ?><div class="col-6 col-lg-3"><?php render_product_card($product); ?></div><?php endforeach; ?>
<?php if (!$products): ?><div class="empty-state">No matches found. Try dress, blazer, sneakers, or tote.</div><?php endif; ?>
</div></div></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
