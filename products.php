<?php
$pageTitle = 'Shop Products - CArtifyX';
include __DIR__ . '/includes/header.php';
$filters = [
    'q' => trim($_GET['q'] ?? ''),
    'category' => trim($_GET['category'] ?? ''),
    'min' => $_GET['min'] ?? '',
    'max' => $_GET['max'] ?? '',
    'sort' => $_GET['sort'] ?? 'newest',
];
$products = get_products($filters);
$categories = get_categories();
?>
<section class="page-hero compact">
    <div class="container"><span class="eyebrow">CArtifyX shop</span><h1>All Products</h1><p>Filter premium fashion by category, price, and mood.</p></div>
</section>
<section class="section-pad">
    <div class="container">
        <form class="filter-bar" method="get">
            <input name="q" placeholder="Search products" value="<?= e($filters['q']) ?>">
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?><option value="<?= e($category['slug']) ?>" <?= $filters['category'] === $category['slug'] ? 'selected' : '' ?>><?= e($category['name']) ?></option><?php endforeach; ?>
            </select>
            <input type="number" name="min" placeholder="Min price" value="<?= e($filters['min']) ?>">
            <input type="number" name="max" placeholder="Max price" value="<?= e($filters['max']) ?>">
            <select name="sort">
                <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest</option>
                <option value="popular" <?= $filters['sort'] === 'popular' ? 'selected' : '' ?>>Popular</option>
                <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Price low to high</option>
                <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Price high to low</option>
            </select>
            <button class="lux-btn" type="submit">Apply</button>
        </form>
        <div class="row g-4 mt-2">
            <?php if (!$products): ?><div class="col-12"><div class="empty-state">No products found. Try a softer filter.</div></div><?php endif; ?>
            <?php foreach ($products as $product): ?><div class="col-6 col-lg-3"><?php render_product_card($product); ?></div><?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
