<?php
$user = me();
if (!$user || $user['status'] !== 'active') go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
$userId = (int) $user['id'];
if (empty($_SESSION['market_csrf'])) $_SESSION['market_csrf'] = bin2hex(random_bytes(32));
$csrfToken = $_SESSION['market_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($csrfToken, $submittedToken)) go('checkout.php', 'แบบฟอร์มหมดอายุ กรุณาลองใหม่', 'danger');
    $productId = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT); $quantity = filter_var($_POST['quantity'] ?? '', FILTER_VALIDATE_INT);
    $address = $_POST['shipping_address'] ?? '';
    if (!is_string($address)) go('checkout.php?product=' . (int) $productId, 'กรุณากรอกที่อยู่จัดส่ง', 'danger');
    $address = trim($address); $addressLength = preg_match_all('/./us', $address);
    if (!$productId || $productId < 1 || $quantity === false || $quantity < 1 || $quantity > 99) go('checkout.php?product=' . (int) $productId, 'เลือกจำนวนสินค้าให้ถูกต้อง', 'danger');
    if ($addressLength < 5 || $addressLength > 500) go('checkout.php?product=' . $productId, 'กรุณากรอกที่อยู่จัดส่ง 5 ถึง 500 ตัวอักษร', 'danger');
    $started = false;
    try {
        $db->begin_transaction(); $started = true;
        $product = $db->query("SELECT id, name, price_thb, stock, eco_impact FROM products WHERE id = $productId AND is_active = 1 FOR UPDATE")->fetch_assoc();
        if (!$product || $quantity > (int) $product['stock']) throw new DomainException('สินค้าหมดหรือมีสินค้าไม่พอ');
        $amountCents = (int) round((float) $product['price_thb'] * 100) * $quantity;
        $account = $db->query("SELECT wallet_balance FROM users WHERE id = $userId FOR UPDATE")->fetch_assoc();
        if ((int) round((float) $account['wallet_balance'] * 100) < $amountCents) throw new DomainException('ยอดเงินใน Wallet ไม่พอ กรุณาเติมเงินก่อนชำระ');
        $orderNumber = 'GL' . date('ymd') . strtoupper(bin2hex(random_bytes(3))); $addressSql = $db->real_escape_string($address); $amount = number_format($amountCents / 100, 2, '.', '');
        $db->query("INSERT INTO orders (order_number, user_id, subtotal, wallet_paid, points_used, discount_amount, shipping_address) VALUES ('$orderNumber', $userId, '$amount', '$amount', 0, '0.00', '$addressSql')");
        $orderId = (int) $db->insert_id; $name = $db->real_escape_string($product['name']); $impact = $db->real_escape_string($product['eco_impact']); $price = number_format((int) round((float) $product['price_thb'] * 100) / 100, 2, '.', '');
        $db->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, eco_impact) VALUES ($orderId, $productId, '$name', $quantity, '$price', '$impact')");
        $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $productId AND stock >= $quantity");
        if ($db->affected_rows !== 1) throw new DomainException('สต็อกเปลี่ยนแปลง กรุณาลองใหม่');
        if ($amountCents > 0) {
            $db->query("UPDATE users SET wallet_balance = wallet_balance - '$amount' WHERE id = $userId AND wallet_balance >= '$amount'");
            if ($db->affected_rows !== 1) throw new DomainException('ยอด Wallet เปลี่ยนแปลง กรุณาลองใหม่');
            $db->query("INSERT INTO wallet_transactions (user_id, transaction_type, amount) VALUES ($userId, 'purchase', '-$amount')");
        }
        $db->commit(); $started = false;
        go('shop.php', 'สั่งซื้อสำเร็จ เลขที่ ' . $orderNumber);
    } catch (DomainException | mysqli_sql_exception $exception) {
        if ($started) $db->rollback();
        if ($exception instanceof mysqli_sql_exception) error_log('Checkout failed: ' . $exception->getMessage());
        $message = $exception instanceof DomainException ? $exception->getMessage() : 'ชำระเงินไม่สำเร็จ กรุณาลองใหม่';
        go('checkout.php?product=' . $productId, $message, 'danger');
    }
}

$wallet = $db->query("SELECT wallet_balance FROM users WHERE id = $userId")->fetch_assoc(); $walletBalance = $wallet['wallet_balance'] ?? '0.00';
$productId = filter_var($_GET['product'] ?? '', FILTER_VALIDATE_INT);
if (!$productId || $productId < 1) go('shop.php');
$product = $db->query("SELECT id, name, price_thb, stock FROM products WHERE id = $productId AND is_active = 1")->fetch_assoc(); if (!$product) go('shop.php', 'สินค้านี้ไม่มีแล้ว', 'danger');

