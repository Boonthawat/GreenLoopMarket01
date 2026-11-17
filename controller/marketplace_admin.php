<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../lib/marketplace.php';

market_require_admin();
$metrics = $db->query("SELECT
    (SELECT COALESCE(SUM(wallet_paid), 0) FROM orders) AS sales,
    (SELECT COALESCE(SUM(points_used), 0) FROM orders) AS points,
    (SELECT COUNT(*) FROM wallet_transactions WHERE transaction_type = 'topup' AND status = 'pending') AS pending_topups,
    (SELECT COUNT(*) FROM products WHERE is_active = 1 AND stock <= 5) AS low_stock")->fetch_assoc();
$products = $db->query('SELECT * FROM products ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
$topups = $db->query("SELECT t.*, u.name, u.email FROM wallet_transactions t JOIN users u ON u.id = t.user_id WHERE t.transaction_type = 'topup' ORDER BY t.created_at DESC")->fetch_all(MYSQLI_ASSOC);
$orders = $db->query("SELECT o.*, u.name, (SELECT GROUP_CONCAT(CONCAT(product_name, ' × ', quantity) SEPARATOR ', ') FROM order_items WHERE order_id = o.id) AS item_list FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC")->fetch_all(MYSQLI_ASSOC);
