<?php
require_once __DIR__ . '/../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') go('../login.php', 'กรุณาเข้าสู่ระบบ', 'danger');

$emailInput = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
if (!is_string($emailInput) || !is_string($password) || $password === '') {
    go('../login.php', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', 'danger');
}
$email = filter_var(trim($emailInput), FILTER_VALIDATE_EMAIL);
if (!$email) go('../login.php', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', 'danger');

$email = $db->real_escape_string($email);
$result = $db->query("SELECT * FROM users WHERE email = '$email' LIMIT 1");
if (!$result) {
    error_log('Login query failed: ' . $db->error);
    go('../login.php', 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่', 'danger');
}
$user = $result->fetch_assoc();
$result->free();
if (!$user || !password_verify($password, $user['password'])) {
    go('../login.php', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', 'danger');
}
if ($user['status'] !== 'active') {
    go('../login.php', 'บัญชีนี้ไม่สามารถเข้าใช้งานได้', 'danger');
}

session_regenerate_id(true);
$_SESSION['uid'] = $_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];
$_SESSION['status'] = $user['status'];
unset($user['password'], $user['answer']);
$_SESSION['user'] = $user;

go($user['role'] === 'admin' ? '../admin.php' : '../index.php', 'เข้าสู่ระบบแล้ว');
