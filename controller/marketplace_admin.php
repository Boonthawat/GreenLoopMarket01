<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if (!$admin) go('../login.php', 'กรุณาล็อกอิน', 'warning');
if ($admin['role'] !== 'admin') go('../index.php', 'สำหรับแอดมิน', 'danger');


if ($_POST && isset($_POST['order_action'])) {
    $orderId = filter_var($_POST['order_id'] ?? '', FILTER_VALIDATE_INT);
    $action = $_POST['order_action'];
    if ($action !== 'approve') go('store.php', 'คำขอไม่ถูกต้อง กรุณาลองใหม่', 'danger');

    $db->begin_transaction();
    $order = $db->query("SELECT * FROM orders WHERE id = $orderId FOR UPDATE")->fetch_assoc();

    $db->query("UPDATE orders SET status = 'processing' WHERE id = $orderId AND status = 'pending_payment'");

    $db->commit();
    go('store.php', 'อนุมัติการชำระเงินแล้ว');
}

$orders = $db->query("SELECT o.*, u.name, (SELECT GROUP_CONCAT(CONCAT(product_name, ' × ', quantity) SEPARATOR ', ') FROM order_items WHERE order_id = o.id) AS item_list FROM orders o JOIN users u ON u.id = o.user_id WHERE o.status = 'pending_payment' ORDER BY o.created_at")->fetch_all(MYSQLI_ASSOC);
