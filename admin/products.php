<?php
$adminTitle = 'Products';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
if (isset($_GET['delete']) && db()) {
    $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: products.php');
    exit;
}
include __DIR__ . '/_layout.php';
$products = db() ? db()->query('SELECT p.*, c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC')->fetchAll() : [];
?>
<div class="admin-card"><table class="admin-table"><thead><tr><th>ID</th><th>Product</th><th>Category</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody><?php foreach($products as $p): ?><tr><td><?= (int)$p['id'] ?></td><td><strong><?= e($p['name']) ?></strong><br><small><?= e($p['brand']) ?></small></td><td><?= e($p['category_name']) ?></td><td><?= money_inr($p['price']) ?></td><td><?= e($p['status']) ?></td><td><a href="edit-product.php?id=<?= (int)$p['id'] ?>">Edit</a> | <a data-confirm="Delete product?" href="products.php?delete=<?= (int)$p['id'] ?>">Delete</a></td></tr><?php endforeach; ?></tbody></table></div>
<?php include __DIR__ . '/_footer.php'; ?>
