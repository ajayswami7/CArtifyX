<?php
$adminTitle = 'Categories';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && db()) {
    $stmt = db()->prepare('INSERT INTO categories (name, slug, image) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), image=VALUES(image)');
    $stmt->execute([$_POST['name'], slugify($_POST['name']), $_POST['image']]);
}
include __DIR__ . '/_layout.php';
$categories = get_categories();
?>
<div class="admin-card"><form class="admin-form" method="post"><input name="name" placeholder="Category name" required><input name="image" placeholder="Image URL"><button class="admin-btn pink">Save Category</button></form></div><div class="admin-card"><table class="admin-table"><tr><th>Name</th><th>Slug</th></tr><?php foreach($categories as $c): ?><tr><td><?= e($c['name']) ?></td><td><?= e($c['slug']) ?></td></tr><?php endforeach; ?></table></div>
<?php include __DIR__ . '/_footer.php'; ?>
