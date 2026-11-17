<?php
require_once __DIR__ . '/../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') go('../register.php', 'กรุณากรอกแบบฟอร์มสมัครสมาชิก', 'danger');

$name = $_POST['name'] ?? '';
$emailInput = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$question = $_POST['question'] ?? '';
$answer = $_POST['answer'] ?? '';

if (!is_string($name) || !is_string($emailInput) || !is_string($phone) || !is_string($password) || !is_string($password2) || !is_string($question) || !is_string($answer)) {
    go('../register.php', 'กรุณาตรวจสอบข้อมูลและรหัสผ่านอีกครั้ง', 'danger');
}
$name = trim($name);
$email = filter_var(trim($emailInput), FILTER_VALIDATE_EMAIL);
$phone = trim($phone);
$question = trim($question);
$answer = trim($answer);

if ($name === '' || !$email || !preg_match('/^0[0-9]{9}$/', $phone) || strlen($password) < 8 || $password !== $password2 || $question === '' || $answer === '') {
    go('../register.php', 'กรุณาตรวจสอบข้อมูลและรหัสผ่านอีกครั้ง', 'danger');
}

$db->set_charset('utf8mb4');
$emailSql = $db->real_escape_string($email);

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$answerHash = password_hash($answer, PASSWORD_DEFAULT);
$nameSql = $db->real_escape_string($name);
$phoneSql = $db->real_escape_string($phone);
$passwordHashSql = $db->real_escape_string($passwordHash);
$questionSql = $db->real_escape_string($question);
$answerHashSql = $db->real_escape_string($answerHash);
$db->query(
    "INSERT INTO users (name, email, phone, points, password, question, answer, role, status)
     VALUES ('$nameSql', '$emailSql', '$phoneSql', 0, '$passwordHashSql', '$questionSql', '$answerHashSql', 'user', 'active')"
);

go('../login.php', 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ');
