<?php
require_once __DIR__ . '/../init.php';

$u = me();
if(!$u) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');

$userId = (int) $u['id'];
$user = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
if (!$user) {
    unset($_SESSION['uid'], $_SESSION['user']);
    go('login.php', 'ไม่พบบัญชีผู้ใช้ กรุณาเข้าสู่ระบบอีกครั้ง', 'warning');
}
$u['points'] = (int) $user['points'];
$_SESSION['user']['points'] = $u['points'];

$sumRecycles = $db->query("SELECT COUNT(*) as n, COALESCE(SUM(weight_kg), 0) as kg , COALESCE(SUM(co2_kg), 0) as co2 FROM recycles where STATUS = 'done'")->fetch_assoc();
$topUser = $db->query("SELECT id,name,points FROM users WHERE role = 'user' AND status = 'active' ORDER BY points DESC, id ASC LIMIT 5")->fetch_all(MYSQLI_ASSOC);