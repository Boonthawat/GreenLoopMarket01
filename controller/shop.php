<?php
$user = market_require_user();
$user['points'] = market_refresh_points($db, (int) $user['id']);
$walletBalance = market_wallet_balance($db, (int) $user['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    market_check_csrf('shop.php');
    $productId = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT);
    if (!$productId || $productId < 1) go('shop.php', 'เลือกสินค้าไม่ถูกต้อง', 'danger');

    $product = $db->query("SELECT id, stock FROM products WHERE id = $productId AND is_active = 1")->fetch_assoc();
    $cart = $_SESSION['market_cart'] ?? [];
    $quantity = (int) ($cart[$productId] ?? 0) + 1;
    if (!$product || $quantity > (int) $product['stock']) {
        go('shop.php', 'สินค้าหมดหรือมีสินค้าไม่พอ', 'danger');
    }

    $cart[$productId] = $quantity;
    $_SESSION['market_cart'] = $cart;
    go('shop.php', 'เพิ่มสินค้าลงตะกร้าแล้ว');
}

$products = $db->query(
    'SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC'
)->fetch_all(MYSQLI_ASSOC);
$cartCount = array_sum($_SESSION['market_cart'] ?? []);

require_once __DIR__ . '/checkout.php';
