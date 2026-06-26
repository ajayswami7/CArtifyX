<?php
$adminTitle = 'Add Product';
require_once __DIR__ . '/../includes/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && db()) {
    $image = trim($_POST['image_url'] ?? '');
    if (!empty($_FILES['image']['name'])) {
        $name = time() . '-' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', $_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $name);
        $image = $name;
    }
    $stmt = db()->prepare('INSERT INTO products (category_id,name,brand,description,price,mrp,image,sizes,colors,stock,status,is_featured,is_bestseller) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([$_POST['category_id'], $_POST['name'], $_POST['brand'], $_POST['description'], $_POST['price'], $_POST['mrp'], $image, $_POST['sizes'], $_POST['colors'], $_POST['stock'], $_POST['status'], isset($_POST['is_featured']) ? 1 : 0, isset($_POST['is_bestseller']) ? 1 : 0]);
    header('Location: products.php');
    exit;
}
include __DIR__ . '/_layout.php';
$categories = get_categories();
?>
<div class="admin-card"><form class="admin-form" method="post" enctype="multipart/form-data"><select name="category_id" required><?php foreach($categories as $c): ?><option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select><input name="name" placeholder="Product name" required><input name="brand" placeholder="Brand" required><textarea name="description" placeholder="Description"></textarea><input type="number" name="price" placeholder="Price" required><input type="number" name="mrp" placeholder="MRP" required><input name="image_url" placeholder="Image URL or upload below"><input type="file" name="image" accept="image/*"><input name="sizes" placeholder="XS,S,M,L"><input name="colors" placeholder="Black,White"><input type="number" name="stock" placeholder="Stock" value="20"><select name="status"><option>active</option><option>inactive</option></select><label><input type="checkbox" name="is_featured"> Featured</label><label><input type="checkbox" name="is_bestseller"> Bestseller</label><button class="admin-btn pink">Save Product</button></form></div>
<?php include __DIR__ . '/_footer.php'; ?>
