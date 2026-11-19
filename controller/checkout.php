<?php
$user = me();
if (!$user || $user['status'] !== 'active') go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
$userId = (int) $user['id'];
if (empty($_SESSION['market_csrf'])) $_SESSION['market_csrf'] = bin2hex(random_bytes(32));
$csrfToken = $_SESSION['market_csrf'];
$paymentQrPath = 'assets/images/promptpay-qr.png';
$paymentQrAvailable = is_file(__DIR__ . '/../' . $paymentQrPath);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($csrfToken, $submittedToken)) go('checkout.php', 'แบบฟอร์มหมดอายุ กรุณาลองใหม่', 'danger');
    $productId = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT); $quantity = filter_var($_POST['quantity'] ?? '', FILTER_VALIDATE_INT);
    $pointsUsed = filter_var($_POST['points_to_use'] ?? '0', FILTER_VALIDATE_INT);
    $address = is_string($_POST['shipping_address'] ?? null) ? trim($_POST['shipping_address']) : '';
    $addressLength = preg_match_all('/./us', $address);
    if (!$productId || $productId < 1 || $quantity === false || $quantity < 1 || $quantity > 99 || $pointsUsed === false || $pointsUsed < 0 || $pointsUsed % 10 !== 0 || $addressLength < 5 || $addressLength > 500) {
        go('checkout.php?product=' . (int) $productId, 'ตรวจสอบจำนวนสินค้าและที่อยู่จัดส่ง', 'danger');
    }

    $db->begin_transaction();
    $product = $db->query("SELECT id, name, price_thb, stock, eco_impact FROM products WHERE id = $productId AND is_active = 1 FOR UPDATE")->fetch_assoc();
    $account = $db->query("SELECT points FROM users WHERE id = $userId FOR UPDATE")->fetch_assoc();
    if (!$product) {
        $db->rollback();
        go('shop.php', 'ไม่พบสินค้าหรือสินค้าถูกปิดขายแล้ว', 'danger');
    }
    if (!$account) {
        $db->rollback();
        go('login.php', 'ไม่พบข้อมูลบัญชี กรุณาเข้าสู่ระบบใหม่', 'danger');
    }
    if ($quantity > (int) $product['stock']) {
        $db->rollback();
        go('checkout.php?product=' . $productId, 'สต็อกเหลือ ' . (int) $product['stock'] . ' ชิ้น แต่เลือก ' . $quantity . ' ชิ้น', 'danger');
    }

    $subtotalCents = (int) round((float) $product['price_thb'] * 100) * $quantity;
    $maxPoints = min(intdiv((int) $account['points'], 10) * 10, intdiv($subtotalCents, 100) * 10);
    if ($pointsUsed > $maxPoints) {
        $db->rollback();
        go('checkout.php?product=' . $productId, 'แต้มไม่พอหรือใช้เกินยอดที่ลดได้', 'danger');
    }

    $discountCents = intdiv($pointsUsed, 10) * 100;
    $amountDueCents = $subtotalCents - $discountCents;
    $directory = __DIR__ . '/../upload/slips';
    $filename = null;
    $slipPath = null;
    if ($amountDueCents > 0) {
        $file = $_FILES['payment_slip'] ?? [];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 5 * 1024 * 1024) {
            $db->rollback();
            go('checkout.php?product=' . $productId, 'อัปโหลดสลิปไม่สำเร็จ หรือไฟล์มีขนาดเกิน 5 MB', 'danger');
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $extension = $extensions[$mime] ?? null;
        if (!$extension || !getimagesize($file['tmp_name'])) {
            $db->rollback();
            go('checkout.php?product=' . $productId, 'รองรับเฉพาะไฟล์ JPG, PNG หรือ WEBP', 'danger');
        }
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            $db->rollback();
            go('checkout.php?product=' . $productId, 'สร้างโฟลเดอร์อัปโหลดไม่สำเร็จ', 'danger');
        }
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
            $db->rollback();
            go('checkout.php?product=' . $productId, 'บันทึกสลิปไม่สำเร็จ', 'danger');
        }
        $slipPath = "'" . $db->real_escape_string('upload/slips/' . $filename) . "'";
    }

    $subtotal = number_format($subtotalCents / 100, 2, '.', '');
    $discount = number_format($discountCents / 100, 2, '.', '');
    $status = $amountDueCents > 0 ? 'pending_payment' : 'processing';
    $orderNumber = 'GL' . date('ymd') . strtoupper(bin2hex(random_bytes(3)));
    $addressSql = $db->real_escape_string($address);
    $db->query("INSERT INTO orders (order_number, user_id, subtotal, wallet_paid, points_used, discount_amount, shipping_address, status, payment_slip_path) VALUES ('$orderNumber', $userId, '$subtotal', 0, $pointsUsed, '$discount', '$addressSql', '$status', $slipPath)");
    $orderId = (int) $db->insert_id; $name = $db->real_escape_string($product['name']); $impact = $db->real_escape_string($product['eco_impact']); $price = number_format((int) round((float) $product['price_thb'] * 100) / 100, 2, '.', '');
    $db->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, eco_impact) VALUES ($orderId, $productId, '$name', $quantity, '$price', '$impact')");
    $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $productId AND stock >= $quantity");
    if ($db->affected_rows !== 1) {
        $db->rollback();
        if ($filename !== null) unlink($directory . '/' . $filename);
        go('checkout.php?product=' . $productId, 'สต็อกเปลี่ยนแปลง กรุณาลองใหม่', 'danger');
    }
    if ($pointsUsed > 0) {
        $db->query("UPDATE users SET points = points - $pointsUsed WHERE id = $userId AND points >= $pointsUsed");
        if ($db->affected_rows !== 1) {
            $db->rollback();
            if ($filename !== null) unlink($directory . '/' . $filename);
            go('checkout.php?product=' . $productId, 'แต้มเปลี่ยนแปลง กรุณาทำรายการใหม่', 'danger');
        }
    }
    $db->commit();
    go('shop.php', $amountDueCents > 0
        ? 'ส่งคำสั่งซื้อและสลิปแล้ว รอแอดมินตรวจสอบ เลขที่ ' . $orderNumber
        : 'ใช้แต้มชำระครบแล้ว คำสั่งซื้อสำเร็จ เลขที่ ' . $orderNumber);
}

$productId = filter_var($_GET['product'] ?? '', FILTER_VALIDATE_INT);
$product = $productId && $productId > 0 ? $db->query("SELECT id, name, price_thb, stock FROM products WHERE id = $productId AND is_active = 1")->fetch_assoc() : null;
if (!$product) go('shop.php', 'สินค้านี้ไม่มีแล้ว', 'danger');
$pointsRow = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
$userPoints = (int) ($pointsRow['points'] ?? 0);
$subtotalCents = (int) round((float) $product['price_thb'] * 100);
$maxRedeemPoints = min(intdiv($userPoints, 10) * 10, intdiv($subtotalCents, 100) * 10);

