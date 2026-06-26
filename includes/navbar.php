<?php $categories = get_categories(); ?>
<nav class="navbar navbar-expand-lg luxury-nav sticky-top">
    <div class="container">
        <a class="navbar-brand brand-mark" href="<?= BASE_URL ?>">CArtifyX</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
                <li class="nav-item dropdown position-static">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Categories</a>
                    <div class="dropdown-menu mega-menu border-0 shadow-lg">
                        <div class="container">
                            <div class="row g-4 py-4">
                                <?php foreach ($categories as $category): ?>
                                    <div class="col-6 col-lg-3">
                                        <a class="mega-link" href="<?= BASE_URL ?>category.php?slug=<?= e($category['slug']) ?>">
                                            <span><?= e($category['name']) ?></span>
                                            <small>New drops, best sellers, edits</small>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>products.php">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>contact.php">Contact</a></li>
            </ul>
            <form class="search-pill position-relative" action="<?= BASE_URL ?>search.php" method="get">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input name="q" autocomplete="off" placeholder="Search CArtifyX" value="<?= e($_GET['q'] ?? '') ?>">
                <div id="searchSuggestions" class="search-suggestions"></div>
            </form>
            <div class="nav-icons">
                <a href="<?= BASE_URL ?>wishlist.php" aria-label="Wishlist"><i class="fa-regular fa-heart"></i><span><?= wishlist_count() ?></span></a>
                <a href="<?= BASE_URL ?>cart.php" aria-label="Cart"><i class="fa-solid fa-bag-shopping"></i><span id="cartCount"><?= cart_count() ?></span></a>
                <?php if (is_logged_in()): ?>
                    <a href="<?= BASE_URL ?>profile.php" aria-label="Profile"><i class="fa-regular fa-user"></i></a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login.php" aria-label="Login"><i class="fa-regular fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
