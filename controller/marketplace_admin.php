<?php
require_once __DIR__ . '/../init.php';

$user = me();
$prefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') !== false ? '../' : '';
if (!$user || $user['status'] !== 'active') {
    go($prefix . 'login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
}
if ($user['role'] !== 'admin') {
    go($prefix . 'index.php', 'หน้านี้สำหรับผู้ดูแลระบบเท่านั้น', 'danger');
}

if (empty($_SESSION['admin_order_csrf'])) {
    $_SESSION['admin_order_csrf'] = bin2hex(random_bytes(32));
}
$adminOrderCsrf = $_SESSION['admin_order_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_action'])) {
    $submittedToken = $_POST['csrf_token'] ?? '';
    $orderId = filter_var($_POST['order_id'] ?? '', FILTER_VALIDATE_INT);
    $action = $_POST['order_action'];
    if (!is_string($submittedToken) || !hash_equals($adminOrderCsrf, $submittedToken) || !$orderId || !in_array($action, ['approve', 'reject'], true)) {
        go('store.php', 'คำขอไม่ถูกต้อง กรุณาลองใหม่', 'danger');
    }

    $db->begin_transaction();
    $order = $db->query("SELECT status, user_id, points_used FROM orders WHERE id = $orderId FOR UPDATE")->fetch_assoc();
    if (!$order || $order['status'] !== 'pending_payment') {
        $db->rollback();
        go('store.php', 'ไม่พบคำสั่งซื้อที่รอตรวจสอบ', 'danger');
    }

    if ($action === 'approve') {
        $db->query("UPDATE orders SET status = 'processing' WHERE id = $orderId AND status = 'pending_payment'");
    } else {
        $items = $db->query("SELECT product_id, quantity FROM order_items WHERE order_id = $orderId")->fetch_all(MYSQLI_ASSOC);
        foreach ($items as $item) {
            if ($item['product_id'] !== null) {
                $productId = (int) $item['product_id'];
                $quantity = (int) $item['quantity'];
                $db->query("UPDATE products SET stock = stock + $quantity WHERE id = $productId");
            }
        }
        $userId = (int) $order['user_id'];
        $pointsUsed = (int) $order['points_used'];
        if ($pointsUsed > 0) {
            $db->query("UPDATE users SET points = points + $pointsUsed WHERE id = $userId");
        }
        $db->query("UPDATE orders SET status = 'payment_rejected' WHERE id = $orderId AND status = 'pending_payment'");
    }
    if ($db->affected_rows !== 1) {
        $db->rollback();
        go('store.php', 'อัปเดตสถานะคำสั่งซื้อไม่สำเร็จ', 'danger');
    }
    $db->commit();
    go('store.php', $action === 'approve' ? 'อนุมัติการชำระเงินแล้ว' : 'ปฏิเสธสลิปและคืนสินค้าเข้าสต็อกแล้ว');
}

$metrics = $db->query("SELECT
    (SELECT COALESCE(SUM(subtotal - discount_amount), 0) FROM orders WHERE status IN ('processing', 'shipped', 'delivered')) AS sales,
    (SELECT COALESCE(SUM(points_used), 0) FROM orders WHERE status IN ('processing', 'shipped', 'delivered')) AS points,
    (SELECT COUNT(*) FROM orders WHERE status = 'pending_payment') AS pending_orders,
    (SELECT COUNT(*) FROM products WHERE is_active = 1 AND stock <= 5) AS low_stock")->fetch_assoc();
$products = $db->query('SELECT * FROM products ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
$orders = $db->query("SELECT o.*, u.name, (SELECT GROUP_CONCAT(CONCAT(product_name, ' × ', quantity) SEPARATOR ', ') FROM order_items WHERE order_id = o.id) AS item_list FROM orders o JOIN users u ON u.id = o.user_id ORDER BY (o.status = 'pending_payment') DESC, o.created_at DESC")->fetch_all(MYSQLI_ASSOC);
