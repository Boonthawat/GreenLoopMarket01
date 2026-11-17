<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');

$adminId = (int) $admin['id'];
$rowNoti = $db->query(
    "SELECT title, message, created_at FROM notifications
     WHERE user_id = $adminId ORDER BY id DESC"
)->fetch_all(MYSQLI_ASSOC);