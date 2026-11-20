<?php
$u = me();
if (!$u || $u['status'] !== 'active') go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
$uId = (int) $u['id'];


// $csrfToken = $_SESSION['market_csrf'];

if ($_POST && isset($_POST['buy_now'])) {
    $submittedToken = $_POST['csrf_token'] ?? '';


    $productId = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'] ?? '', FILTER_VALIDATE_INT);
    $pointsUsed = filter_var($_POST['points_to_use'] ?? '0', FILTER_VALIDATE_INT);
    $address = is_string($_POST['shipping_address'] ?? null) ? trim($_POST['shipping_address']) : '';


    $db->begin_transaction();
    $product = $db->query("SELECT * FROM products WHERE id = $productId AND is_active = 1 FOR UPDATE")->fetch_assoc();
    $account = $db->query("SELECT points FROM users WHERE id = $uId FOR UPDATE")->fetch_assoc();
    $unitPriceCents = (int) round((float) $product['price_thb'] * 100);
    $subtotalCents = $unitPriceCents * $quantity;
    $maxPoints = min(intdiv((int) $account['points'], 10), intdiv($subtotalCents, 100)) * 10;
    if ($pointsUsed > $maxPoints) {
        $db->rollback();
        go('checkout.php?product=' . $productId, 'แต้มไม่พอหรือใช้เกินยอดที่ลดได้', 'danger');
    }

    $discountCents = $pointsUsed * 10;
    $amountDueCents = $subtotalCents - $discountCents;
    $subtotal = number_format($subtotalCents / 100, 2, '.', '');
    $discount = number_format($discountCents / 100, 2, '.', '');
    $status = 'pending_payment';
    $orderNumber = date('ymd') . random_int(100, 999);
    $db->query("INSERT INTO orders (order_number, user_id, subtotal, wallet_paid, points_used, discount_amount, shipping_address, status, payment_slip_path) VALUES ('$orderNumber', $uId, '$subtotal', 0, $pointsUsed, '$discount', '$address', '$status', NULL)");
    
    
    
    $orderId = (int) $db->insert_id;
    $name = $db->real_escape_string($product['name']);
    $impact = $db->real_escape_string($product['eco_impact']);
    $price = number_format($unitPriceCents / 100, 2, '.', '');
    $db->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, eco_impact) VALUES ($orderId, $productId, '$name', $quantity, '$price', '$impact')");
    $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $productId AND stock >= $quantity");
    if ($pointsUsed > 0) {
        $db->query("UPDATE users SET points = points - $pointsUsed WHERE id = $uId AND points >= $pointsUsed");
        if ($db->affected_rows !== 1) {
            $db->rollback();
            go('checkout.php?product=' . $productId, 'แต้มเปลี่ยนแปลง กรุณาทำรายการใหม่', 'danger');
        }
    }

    $db->commit();
    go('shop.php', 'เลขที่ ' . $orderNumber);
}

$productId = (int) filter_var($_GET['product'] ?? '', FILTER_VALIDATE_INT);
$product = $db->query("SELECT * FROM products WHERE id = $productId AND is_active = 1")->fetch_assoc();

$userPoints = (int) ($db->query("SELECT points FROM users WHERE id = $uId")->fetch_assoc()['points'] ?? 0);
