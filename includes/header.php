<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? APP_NAME . ' - Luxury Fashion Shopping';
$pageDescription = $pageDescription ?? 'Shop premium fashion, footwear, accessories, and curated Myntra-inspired styles at CArtifyX.';
$flash = consume_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/responsive.css" rel="stylesheet">
</head>
<body>
<div id="pageLoader"><span></span></div>
<?php include __DIR__ . '/navbar.php'; ?>
<main class="site-main">
<?php if ($flash): ?>
    <div class="toast align-items-center text-bg-<?= e($flash['type'] === 'danger' ? 'danger' : ($flash['type'] === 'warning' ? 'warning' : 'dark')) ?> border-0 show app-toast" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= e($flash['message']) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
<?php endif; ?>
