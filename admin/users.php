<?php $adminTitle = 'Users'; include __DIR__ . '/_layout.php'; $users = db()?db()->query('SELECT id,name,email,phone,created_at FROM users ORDER BY id DESC')->fetchAll():[]; ?>
<div class="admin-card"><table class="admin-table"><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr><?php foreach($users as $u): ?><tr><td><?= (int)$u['id'] ?></td><td><?= e($u['name']) ?></td><td><?= e($u['email']) ?></td><td><?= e($u['phone']) ?></td><td><?= e($u['created_at']) ?></td></tr><?php endforeach; ?></table></div>
<?php include __DIR__ . '/_footer.php'; ?>
