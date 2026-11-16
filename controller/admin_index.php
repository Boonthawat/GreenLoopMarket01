<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');

$newContents = $db->query("SELECT * FROM contents ORDER BY start_at DESC")->fetch_all(MYSQLI_ASSOC);
$sumRecycles = $db->query("SELECT COUNT(*) as n, COALESCE(SUM(weight_kg), 0) as kg , COALESCE(SUM(co2_kg), 0) as co2 FROM recycles where STATUS = 'done'")->fetch_all(MYSQLI_ASSOC);

$MC = $db->query("SELECT COUNT(*) FROM users WHERE status = 'active' AND role = 'user' ORDER BY id DESC")->fetch_row()[0];
$MCS =  $db->query("SELECT COUNT(*) FROM users WHERE status = 'suspended' AND role = 'user' ORDER BY id DESC")->fetch_row()[0];
$contentCount = $db->query("SELECT COUNT(*) FROM contents ORDER BY id DESC ")->fetch_row()[0];
$reDone = $db->query("SELECT COUNT(*) FROM recycles WHERE status = 'done' ORDER BY id DESC ")->fetch_row()[0];
$rePending = $db->query("SELECT COUNT(*) FROM recycles WHERE status = 'pending' ORDER BY id DESC ")->fetch_row()[0];
$notReadNoti = $db->query("SELECT COUNT(*) FROM notifications WHERE  is_read = 0 ORDER BY id DESC ")->fetch_row()[0];