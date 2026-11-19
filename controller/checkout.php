<?php
$u = me();
if (!$u || $u['status'] !== 'active') go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
$uId = (int) $u['id'];

if (empty($_SESSION['market_csrf'])) $_SESSION['market_csrf'] = bin2hex(random_bytes(32));
$csrfToken = $_SESSION['market_csrf'];
$paymentQrPath = 'assets/images/promptpay-qr.png';
$paymentQrAvailable = is_file(__DIR__ . '/../' . $paymentQrPath);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($csrfToken, $submittedToken)) {
        go('checkout.php', 'แบบฟอร์มหมดอายุ กรุณาลองใหม่', 'danger');
    }

    $productId = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'] ?? '', FILTER_VALIDATE_INT);
    $pointsUsed = filter_var($_POST['points_to_use'] ?? '0', FILTER_VALIDATE_INT);
    $address = is_string($_POST['shipping_address'] ?? null) ? trim($_POST['shipping_address']) : '';
    $addressLength = preg_match_all('/./us', $address);
    if (
        $productId === false || $productId < 1 ||
        $quantity === false || $quantity < 1 || $quantity > 99 ||
        $pointsUsed === false || $pointsUsed < 0 || $pointsUsed % 10 !== 0 ||
        $addressLength < 5 || $addressLength > 500
    ) {
        go('checkout.php?product=' . (int) $productId, 'ตรวจสอบจำนวนสินค้าและที่อยู่จัดส่ง', 'danger');
    }

    $directory = __DIR__ . '/../upload/slips';
    $filename = null;
    $failCheckout = static function (string $message) use ($db, $directory, $productId, &$filename): void {
        $db->rollback();
        $path = $directory . DIRECTORY_SEPARATOR . ($filename ?? '');
        if ($filename && is_file($path)) unlink($path);
        go('checkout.php?product=' . $productId, $message, 'danger');
    };

    $saveSlip = static function () use ($directory) {
        $file = $_FILES['payment_slip'] ?? [];
        $tmp = $file['tmp_name'] ?? '';
        if (
            ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK ||
            ($file['size'] ?? 0) > 5 * 1024 * 1024 ||
            !is_string($tmp) ||
            !is_uploaded_file($tmp)
        ) {
            return false;
        }

        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $ext = $extensions[(new finfo(FILEINFO_MIME_TYPE))->file($tmp)] ?? null;
        if (
            !$ext ||
            !getimagesize($tmp) ||
            (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory))
        ) {
            return false;
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        return move_uploaded_file($tmp, $directory . DIRECTORY_SEPARATOR . $filename) ? $filename : false;
    };

    $db->begin_transaction();
    $product = $db->query("SELECT id, name, price_thb, stock, eco_impact FROM products WHERE id = $productId AND is_active = 1 FOR UPDATE")->fetch_assoc();
    $account = $db->query("SELECT points FROM users WHERE id = $uId FOR UPDATE")->fetch_assoc();
    $unitPriceCents = (int) round((float) $product['price_thb'] * 100);
    $subtotalCents = $unitPriceCents * $quantity;
    $maxPoints = min(intdiv((int) $account['points'], 10) * 10, intdiv($subtotalCents, 100) * 10);
    if ($pointsUsed > $maxPoints) $failCheckout('แต้มไม่พอหรือใช้เกินยอดที่ลดได้');

    $discountCents = $pointsUsed * 10;
    $amountDueCents = $subtotalCents - $discountCents;
    $slipPath = null;
    if ($amountDueCents > 0) {
        $filename = $saveSlip();
        if (!$filename) $failCheckout('กรุณาแนบสลิป JPG, PNG หรือ WEBP ขนาดไม่เกิน 5 MB');
        $slipPath = "'" . $db->real_escape_string('upload/slips/' . $filename) . "'";
    }

    $subtotal = number_format($subtotalCents / 100, 2, '.', '');
    $discount = number_format($discountCents / 100, 2, '.', '');
    $status = $amountDueCents > 0 ? 'pending_payment' : 'processing';
    $orderNumber = 'GL' . date('ymd') . strtoupper(bin2hex(random_bytes(3)));
    $addressSql = $db->real_escape_string($address);
    $db->query("INSERT INTO orders (order_number, user_id, subtotal, wallet_paid, points_used, discount_amount, shipping_address, status, payment_slip_path) VALUES ('$orderNumber', $uId, '$subtotal', 0, $pointsUsed, '$discount', '$addressSql', '$status', $slipPath)");
    $orderId = (int) $db->insert_id;
    $name = $db->real_escape_string($product['name']);
    $impact = $db->real_escape_string($product['eco_impact']);
    $price = number_format($unitPriceCents / 100, 2, '.', '');
    $db->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, eco_impact) VALUES ($orderId, $productId, '$name', $quantity, '$price', '$impact')");
    $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $productId AND stock >= $quantity");
    if ($db->affected_rows !== 1) $failCheckout('สต็อกเปลี่ยนแปลง กรุณาลองใหม่');
    if ($pointsUsed > 0) {
        $db->query("UPDATE users SET points = points - $pointsUsed WHERE id = $uId AND points >= $pointsUsed");
        if ($db->affected_rows !== 1) $failCheckout('แต้มเปลี่ยนแปลง กรุณาทำรายการใหม่');
    }
    $db->commit();
    go('shop.php', ($amountDueCents > 0 ? 'ส่งคำสั่งซื้อและสลิปแล้ว รอแอดมินตรวจสอบ เลขที่ ' : 'ใช้แต้มชำระครบแล้ว คำสั่งซื้อสำเร็จ เลขที่ ') . $orderNumber);
}

$productId = filter_var($_GET['product'] ?? '', FILTER_VALIDATE_INT);
$product = $productId && $productId > 0
    ? $db->query("SELECT id, name, price_thb, stock FROM products WHERE id = $productId AND is_active = 1")->fetch_assoc()
    : null;
if (!$product) go('shop.php', 'สินค้านี้ไม่มีแล้ว', 'danger');
$pointsRow = $db->query("SELECT points FROM users WHERE id = $uId")->fetch_assoc();
$userPoints = (int) ($pointsRow['points'] ?? 0);
$subtotalCents = (int) round((float) $product['price_thb'] * 100);
$maxRedeemPoints = min(intdiv($userPoints, 10) * 10, intdiv($subtotalCents, 100) * 10);

