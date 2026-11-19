<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');

$adminId = (int) $admin['id'];
$db->query("UPDATE notifications SET is_read = 1 WHERE (user_id = $adminId OR user_id IS NULL) AND title = 'สมาชิกใหม่' AND is_read = 0");
$rows = $db->query("SELECT title, message, created_at FROM notifications WHERE (user_id = $adminId OR user_id IS NULL) AND title = 'สมาชิกใหม่' ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
