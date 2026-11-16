<?php
require_once __DIR__ . '/../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') go('forgot.php', 'กรุณากรอกแบบฟอร์ม', 'danger');

$emailInput = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$question = $_POST['question'] ?? '';
$answer = $_POST['answer'] ?? '';

if (!is_string($emailInput) || !is_string($password) || !is_string($password2) || !is_string($question) || !is_string($answer)) {
    go('forgot.php', 'กรุณาตรวจสอบข้อมูลและรหัสผ่านอีกครั้ง', 'danger');
}
$email = filter_var(trim($emailInput), FILTER_VALIDATE_EMAIL);
$question = trim($question);
$answer = trim($answer);
if (!$email || strlen($password) < 8 || $password !== $password2 || $question === '' || $answer === '') {
    go('forgot.php', 'กรุณาตรวจสอบข้อมูลและรหัสผ่านอีกครั้ง', 'danger');
}

$statement = $db->prepare('SELECT id, question, answer FROM users WHERE email = ? LIMIT 1');
$statement->bind_param('s', $email);
$statement->execute();
$user = $statement->get_result()->fetch_assoc();
$statement->close();
if (!$user || $user['question'] !== $question || !password_verify($answer, $user['answer'])) {
    go('forgot.php', 'อีเมล คำถาม หรือคำตอบไม่ถูกต้อง', 'danger');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$userId = (int) $user['id'];
$statement = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
$statement->bind_param('si', $passwordHash, $userId);
$statement->execute();
$statement->close();
go('login.php', 'เปลี่ยนรหัสผ่านแล้ว กรุณาเข้าสู่ระบบ');
