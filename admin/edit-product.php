<?php
$adminTitle = 'Edit Product';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
$id = (int)($_GET['id'] ?? 0);
$product = $id ? get_product($id) : null;
if (!$product) { header('Location: products.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && db()) {
    $image = trim($_POST['image_url'] ?? $product['image']);
    if (!empty($_FILES['image']['name'])) {
        $name = time() . '-' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $name);
        $image = $name;
    }
    $stmt = db()->prepare('UPDATE products SET category_id=?,name=?,brand=?,description=?,price=?,mrp=?,image=?,sizes=?,colors=?,stock=?,status=?,is_featured=?,is_bestseller=? WHERE id=?');
    $stmt->execute([$_POST['category_id'], $_POST['name'], $_POST['brand'], $_POST['description'], $_POST['price'], $_POST['mrp'], $image, $_POST['sizes'], $_POST['colors'], $_POST['stock'], $_POST['status'], isset($_POST['is_featured']) ? 1 : 0, isset($_POST['is_bestseller']) ? 1 : 0, $id]);
    header('Location: products.php');
    exit;
}
include __DIR__ . '/_layout.php';
$categories = get_categories();
?>
<div class="admin-card"><form class="admin-form" method="post" enctype="multipart/form-data"><select name="category_id" required><?php foreach($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (int)$product['category_id']===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select><input name="name" value="<?= e($product['name']) ?>" required><input name="brand" value="<?= e($product['brand']) ?>" required><textarea name="description"><?= e($product['description']) ?></textarea><input type="number" name="price" value="<?= e($product['price']) ?>" required><input type="number" name="mrp" value="<?= e($product['mrp']) ?>" required><input name="image_url" value="<?= e($product['image']) ?>"><input type="file" name="image" accept="image/*"><input name="sizes" value="<?= e($product['sizes']) ?>"><input name="colors" value="<?= e($product['colors']) ?>"><input type="number" name="stock" value="<?= e($product['stock'] ?? 0) ?>"><select name="status"><option <?= $product['status']==='active'?'selected':'' ?>>active</option><option <?= $product['status']==='inactive'?'selected':'' ?>>inactive</option></select><label><input type="checkbox" name="is_featured" <?= !empty($product['is_featured'])?'checked':'' ?>> Featured</label><label><input type="checkbox" name="is_bestseller" <?= !empty($product['is_bestseller'])?'checked':'' ?>> Bestseller</label><button class="admin-btn pink">Update Product</button></form></div>
<?php include __DIR__ . '/_footer.php'; ?>
