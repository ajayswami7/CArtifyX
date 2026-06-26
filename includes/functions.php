<?php
require_once __DIR__ . '/config.php';

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    if (preg_match('/^https?:\/\//', $path)) {
        header('Location: ' . $path);
    } else {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
    }
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function consume_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('warning', 'Please login to continue.');
        redirect('login.php');
    }
}

function require_admin(): void
{
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user(): ?array
{
    if (!is_logged_in() || !db()) {
        return null;
    }
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function money_inr($amount): string
{
    $amount = (float)$amount;
    $formatted = number_format($amount, 2, '.', '');
    [$integer, $decimal] = explode('.', $formatted);
    $last3 = substr($integer, -3);
    $rest = substr($integer, 0, -3);
    if ($rest !== '') {
        $last3 = ',' . $last3;
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
    }
    $value = $rest . $last3;
    return '&#8377;' . ($decimal === '00' ? $value : $value . '.' . $decimal);
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-') ?: 'item';
}

function get_categories(): array
{
    if (!db()) {
        return sample_categories();
    }
    return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
}

function get_category_by_slug(string $slug): ?array
{
    if (!db()) {
        foreach (sample_categories() as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }
        return null;
    }
    $stmt = db()->prepare('SELECT * FROM categories WHERE slug = ?');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function get_products(array $filters = []): array
{
    if (!db()) {
        return filter_sample_products($filters);
    }
    $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.status = "active"';
    $params = [];
    if (!empty($filters['category'])) {
        $sql .= ' AND c.slug = ?';
        $params[] = $filters['category'];
    }
    if (!empty($filters['q'])) {
        $sql .= ' AND (p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)';
        $term = '%' . $filters['q'] . '%';
        array_push($params, $term, $term, $term);
    }
    if (!empty($filters['min'])) {
        $sql .= ' AND p.price >= ?';
        $params[] = (float)$filters['min'];
    }
    if (!empty($filters['max'])) {
        $sql .= ' AND p.price <= ?';
        $params[] = (float)$filters['max'];
    }
    $sort = $filters['sort'] ?? 'newest';
    $sql .= match ($sort) {
        'price_low' => ' ORDER BY p.price ASC',
        'price_high' => ' ORDER BY p.price DESC',
        'popular' => ' ORDER BY p.is_bestseller DESC, p.id DESC',
        default => ' ORDER BY p.id DESC',
    };
    if (!empty($filters['limit'])) {
        $sql .= ' LIMIT ' . (int)$filters['limit'];
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_product(int $id): ?array
{
    if (!db()) {
        foreach (sample_products() as $product) {
            if ((int)$product['id'] === $id) {
                return $product;
            }
        }
        return null;
    }
    $stmt = db()->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function product_image(array $product): string
{
    if (!empty($product['image'])) {
        if (preg_match('/^https?:\/\//', $product['image'])) {
            return $product['image'];
        }
        return UPLOAD_URL . ltrim($product['image'], '/');
    }
    return 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=80';
}

function product_discount(array $product): int
{
    $mrp = (float)($product['mrp'] ?? 0);
    $price = (float)($product['price'] ?? 0);
    return $mrp > $price ? (int)round((($mrp - $price) / $mrp) * 100) : 0;
}

function cart_count(): int
{
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return (int)$stmt->fetchColumn();
    }
    return array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));
}

function wishlist_count(): int
{
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return (int)$stmt->fetchColumn();
    }
    return count($_SESSION['wishlist'] ?? []);
}

function add_to_cart(int $productId, int $qty = 1, string $size = '', string $color = ''): void
{
    $qty = max(1, $qty);
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?');
        $stmt->execute([$_SESSION['user_id'], $productId, $size, $color]);
        $row = $stmt->fetch();
        if ($row) {
            $update = db()->prepare('UPDATE cart SET quantity = quantity + ? WHERE id = ?');
            $update->execute([$qty, $row['id']]);
        } else {
            $insert = db()->prepare('INSERT INTO cart (user_id, product_id, quantity, size, color) VALUES (?, ?, ?, ?, ?)');
            $insert->execute([$_SESSION['user_id'], $productId, $qty, $size, $color]);
        }
        return;
    }
    $key = $productId . ':' . $size . ':' . $color;
    $_SESSION['cart'][$key] = $_SESSION['cart'][$key] ?? ['product_id' => $productId, 'quantity' => 0, 'size' => $size, 'color' => $color];
    $_SESSION['cart'][$key]['quantity'] += $qty;
}

function get_cart_items(): array
{
    $items = [];
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('SELECT cart.*, products.name, products.brand, products.price, products.mrp, products.image FROM cart JOIN products ON products.id = cart.product_id WHERE cart.user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll();
    }
    foreach ($_SESSION['cart'] ?? [] as $key => $item) {
        $product = get_product((int)$item['product_id']);
        if ($product) {
            $items[] = array_merge($item, $product, ['id' => $key]);
        }
    }
    return $items;
}

function cart_totals(array $items): array
{
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += (float)$item['price'] * (int)$item['quantity'];
    }
    $gst = round($subtotal * 0.18, 2);
    $shipping = $subtotal > 1999 || $subtotal == 0 ? 0 : 99;
    return ['subtotal' => $subtotal, 'gst' => $gst, 'shipping' => $shipping, 'total' => $subtotal + $gst + $shipping];
}

function add_to_wishlist(int $productId): void
{
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)');
        $stmt->execute([$_SESSION['user_id'], $productId]);
        return;
    }
    $_SESSION['wishlist'][$productId] = $productId;
}

function remove_from_wishlist(int $productId): void
{
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('DELETE FROM wishlist WHERE user_id = ? AND product_id = ?');
        $stmt->execute([$_SESSION['user_id'], $productId]);
        return;
    }
    unset($_SESSION['wishlist'][$productId]);
}

function get_wishlist_items(): array
{
    if (is_logged_in() && db()) {
        $stmt = db()->prepare('SELECT p.*, w.id AS wishlist_id FROM wishlist w JOIN products p ON p.id = w.product_id WHERE w.user_id = ? ORDER BY w.id DESC');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll();
    }
    $items = [];
    foreach ($_SESSION['wishlist'] ?? [] as $productId) {
        $product = get_product((int)$productId);
        if ($product) {
            $items[] = $product;
        }
    }
    return $items;
}

function render_product_card(array $product): void
{
    $payload = [
        'id' => (int)$product['id'],
        'name' => $product['name'],
        'brand' => $product['brand'],
        'image' => product_image($product),
        'description' => $product['description'] ?? '',
        'price' => money_inr($product['price']),
    ];
    ?>
    <article class="product-card">
        <div class="product-media">
            <a href="<?= BASE_URL ?>single-product.php?id=<?= (int)$product['id'] ?>">
                <img src="<?= e(product_image($product)) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
            </a>
            <?php if (product_discount($product)): ?><span class="discount"><?= product_discount($product) ?>% OFF</span><?php endif; ?>
            <div class="product-actions">
                <button type="button" class="quick-view" data-product='<?= e(json_encode($payload)) ?>' aria-label="Quick view"><i class="fa-regular fa-eye"></i></button>
                <button type="button" data-cart-action="add" data-product-id="<?= (int)$product['id'] ?>" aria-label="Add to cart"><i class="fa-solid fa-bag-shopping"></i></button>
                <a href="<?= BASE_URL ?>wishlist.php?action=add&id=<?= (int)$product['id'] ?>" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></a>
            </div>
        </div>
        <div class="product-body">
            <div class="brand"><?= e($product['brand']) ?></div>
            <h3><a href="<?= BASE_URL ?>single-product.php?id=<?= (int)$product['id'] ?>"><?= e($product['name']) ?></a></h3>
            <div class="price-row"><span class="price"><?= money_inr($product['price']) ?></span><span class="mrp"><?= money_inr($product['mrp'] ?? $product['price']) ?></span></div>
        </div>
    </article>
    <?php
}

function admin_metric(string $table): int
{
    $allowed = ['users', 'products', 'categories', 'orders'];
    if (!db() || !in_array($table, $allowed, true)) {
        return 0;
    }
    return (int)db()->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
}

function sample_categories(): array
{
    return [
        ['id' => 1, 'name' => 'Women', 'slug' => 'women', 'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80'],
        ['id' => 2, 'name' => 'Men', 'slug' => 'men', 'image' => 'https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=900&q=80'],
        ['id' => 3, 'name' => 'Footwear', 'slug' => 'footwear', 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&q=80'],
        ['id' => 4, 'name' => 'Accessories', 'slug' => 'accessories', 'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=900&q=80'],
    ];
}

function sample_products(): array
{
    return [
        ['id' => 1, 'category_id' => 1, 'category_name' => 'Women', 'category_slug' => 'women', 'name' => 'Satin Wrap Midi Dress', 'brand' => 'Aurelia Noir', 'price' => 2499, 'mrp' => 3999, 'image' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=80', 'description' => 'Liquid satin wrap dress with a polished evening drape.', 'sizes' => 'XS,S,M,L,XL', 'colors' => 'Rose,Black,Champagne', 'is_bestseller' => 1, 'is_featured' => 1],
        ['id' => 2, 'category_id' => 2, 'category_name' => 'Men', 'category_slug' => 'men', 'name' => 'Tailored Linen Blazer', 'brand' => 'Maison Mode', 'price' => 4299, 'mrp' => 6999, 'image' => 'https://images.unsplash.com/photo-1506629905607-d405b7a30db9?auto=format&fit=crop&w=900&q=80', 'description' => 'Breathable linen blazer with a clean premium profile.', 'sizes' => 'S,M,L,XL', 'colors' => 'Ivory,Navy,Black', 'is_bestseller' => 1, 'is_featured' => 1],
        ['id' => 3, 'category_id' => 3, 'category_name' => 'Footwear', 'category_slug' => 'footwear', 'name' => 'Premium Sneaker Drop', 'brand' => 'Street Luxe', 'price' => 3299, 'mrp' => 5499, 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', 'description' => 'Clean street sneakers with cushioned soles.', 'sizes' => '6,7,8,9,10', 'colors' => 'White,Black,Tan', 'is_bestseller' => 1, 'is_featured' => 0],
        ['id' => 4, 'category_id' => 4, 'category_name' => 'Accessories', 'category_slug' => 'accessories', 'name' => 'Structured Tote Bag', 'brand' => 'Velvet Edit', 'price' => 1999, 'mrp' => 3499, 'image' => 'https://images.unsplash.com/photo-1594223274512-ad4803739b7c?auto=format&fit=crop&w=900&q=80', 'description' => 'Roomy tote with polished metal hardware.', 'sizes' => 'One Size', 'colors' => 'Tan,Black,Burgundy', 'is_bestseller' => 0, 'is_featured' => 1],
        ['id' => 5, 'category_id' => 1, 'category_name' => 'Women', 'category_slug' => 'women', 'name' => 'Minimal Co-ord Set', 'brand' => 'CArtifyX Studio', 'price' => 2899, 'mrp' => 4499, 'image' => 'https://images.unsplash.com/photo-1550614000-4895a10e1bfd?auto=format&fit=crop&w=900&q=80', 'description' => 'A soft co-ord made for travel, brunch, and office Fridays.', 'sizes' => 'XS,S,M,L', 'colors' => 'Oat,Olive,Black', 'is_bestseller' => 0, 'is_featured' => 1],
        ['id' => 6, 'category_id' => 2, 'category_name' => 'Men', 'category_slug' => 'men', 'name' => 'Oversized Resort Shirt', 'brand' => 'North Label', 'price' => 1599, 'mrp' => 2599, 'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=80', 'description' => 'A relaxed shirt in a premium cotton blend.', 'sizes' => 'S,M,L,XL,XXL', 'colors' => 'White,Sage,Ink', 'is_bestseller' => 1, 'is_featured' => 1],
    ];
}

function filter_sample_products(array $filters): array
{
    $products = sample_products();
    if (!empty($filters['category'])) {
        $products = array_filter($products, fn($p) => $p['category_slug'] === $filters['category']);
    }
    if (!empty($filters['q'])) {
        $q = strtolower($filters['q']);
        $products = array_filter($products, fn($p) => str_contains(strtolower($p['name'] . ' ' . $p['brand'] . ' ' . $p['description']), $q));
    }
    if (!empty($filters['min'])) {
        $products = array_filter($products, fn($p) => $p['price'] >= (float)$filters['min']);
    }
    if (!empty($filters['max'])) {
        $products = array_filter($products, fn($p) => $p['price'] <= (float)$filters['max']);
    }
    $sort = $filters['sort'] ?? 'newest';
    usort($products, function ($a, $b) use ($sort) {
        return match ($sort) {
            'price_low' => $a['price'] <=> $b['price'],
            'price_high' => $b['price'] <=> $a['price'],
            'popular' => $b['is_bestseller'] <=> $a['is_bestseller'],
            default => $b['id'] <=> $a['id'],
        };
    });
    if (!empty($filters['limit'])) {
        $products = array_slice($products, 0, (int)$filters['limit']);
    }
    return array_values($products);
}
