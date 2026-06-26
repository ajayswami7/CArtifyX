<?php
require_once __DIR__ . '/../includes/functions.php';
header('Location: ' . (is_admin_logged_in() ? 'dashboard.php' : 'login.php'));
exit;
