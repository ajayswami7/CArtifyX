<?php
$pageTitle = 'CArtifyX - Luxury Fashion Shopping';
include __DIR__ . '/includes/header.php';
$featured = get_products(['limit' => 8, 'sort' => 'popular']);
$newArrivals = get_products(['limit' => 4]);
$categories = get_categories();
?>
<section id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1800&q=80')">
                <div class="container hero-content">
                    <span class="eyebrow">Luxury fashion edit</span>
                    <h1>CArtifyX</h1>
                    <p class="lead">Premium Myntra-inspired shopping with crisp edits, smooth discovery.</p>
                    <a class="lux-btn" href="products.php">Shop Collection</a>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1800&q=80')">
                <div class="container hero-content">
                    <span class="eyebrow">Quiet luxury essentials</span>
                    <h1>Modern style, edited sharply</h1>
                    <p class="lead">Tailored fits, occasionwear, footwear, and polished accessories for every wardrobe rhythm.</p>
                    <a class="lux-btn" href="products.php?sort=popular">Explore Best Sellers</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
</section>

<section class="section-pad">
    <div class="container">
        <div class="section-title">
            <div><span class="eyebrow">Explore</span><h2>Trending Categories</h2></div>
            <p>Fashion edits for daily wear, elevated office looks, accessories, and statement footwear.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-6 col-lg-3">
                    <a class="category-tile" href="category.php?slug=<?= e($category['slug']) ?>" style="background-image:url('<?= e($category['image'] ?? '') ?>')">
                        <div><h3><?= e($category['name']) ?></h3><span>Shop now</span></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad section-soft">
    <div class="container">
        <div class="section-title">
            <div><span class="eyebrow">Curated</span><h2>Featured Products</h2></div>
            <a href="products.php" class="btn-ghost">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured as $product): ?>
                <div class="col-6 col-lg-3"><?php render_product_card($product); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="banner-band">
            <div class="col-lg-7">
                <span class="eyebrow text-white">Private sale</span>
                <h2>Up to 50% off premium styles</h2>
                <p>Sharp silhouettes, soft textures, and statement pieces in one polished CArtifyX edit.</p>
                <a class="lux-btn light" href="products.php?sort=popular">Shop Best Sellers</a>
            </div>
        </div>
    </div>
</section>

<section class="section-pad pt-0">
    <div class="container">
        <div class="section-title"><div><span class="eyebrow">Just in</span><h2>New Arrivals</h2></div><p>Fresh pieces for a cleaner, smarter daily rotation.</p></div>
        <div class="row g-4">
            <?php foreach ($newArrivals as $product): ?>
                <div class="col-6 col-lg-3"><?php render_product_card($product); ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="newsletter-band">
    <div class="container">
        <h2>Join the CArtifyX circle</h2>
        <p>Early access to drops, sale previews, and style edits.</p>
        <form method="post" action="contact.php">
            <input type="email" name="newsletter_email" placeholder="Email address" required>
            <button>Subscribe</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
