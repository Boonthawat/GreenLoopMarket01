<?php
$user = me();
if (!$user || $user['status'] !== 'active') {
    go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
}
$userId = (int) $user['id'];

$pointsRow = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
$user['points'] = (int) ($pointsRow['points'] ?? 0);
$_SESSION['user']['points'] = $user['points'];

$products = $db->query(
    'SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC'
)->fetch_all(MYSQLI_ASSOC);
