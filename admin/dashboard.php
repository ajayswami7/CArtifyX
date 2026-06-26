<?php $adminTitle = 'Dashboard'; include __DIR__ . '/_layout.php'; ?>
<div class="metric-grid">
<div class="metric-card"><i class="fa-solid fa-users"></i><h3><?= admin_metric('users') ?></h3><p>Customers</p></div>
<div class="metric-card"><i class="fa-solid fa-shirt"></i><h3><?= admin_metric('products') ?></h3><p>Products</p></div>
<div class="metric-card"><i class="fa-solid fa-layer-group"></i><h3><?= admin_metric('categories') ?></h3><p>Categories</p></div>
<div class="metric-card"><i class="fa-solid fa-receipt"></i><h3><?= admin_metric('orders') ?></h3><p>Orders</p></div>
</div>
<div class="admin-card"><h2>Sales Summary</h2><div id="salesBars" style="height:220px;display:flex;align-items:end;gap:18px;border-bottom:1px solid #ddd;padding-top:24px"><?php foreach([45,70,52,85,64,92,78] as $i=>$h): ?><div style="flex:1;text-align:center"><div class="bar" data-height="<?= $h ?>" style="height:4%;transition:.6s;background:linear-gradient(#ff3f6c,#111);border-radius:12px 12px 0 0"></div><small>Day <?= $i+1 ?></small></div><?php endforeach; ?></div></div>
<?php include __DIR__ . '/_footer.php'; ?>
