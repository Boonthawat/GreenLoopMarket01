<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');

$adminId = (int) $admin['id'];
$db->query("UPDATE notifications SET is_read = 0 ,title = 'สมาชิกใหม่' WHERE user_id = $adminId " );
$rowNoti = $db->query("SELECT title,message,created_at FROM notifications WHERE id = '$admin' AND title = 'สมาชิกใหม่' ORDER BY id desc ")->fetch_all(MYSQLI_ASSOC);