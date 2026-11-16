<?php
require_once __DIR__ . '/../init.php';

$u = me();
if(!$u) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');


// var_dump($_POST['save']);exit;

if(isset($_POST['save'])){
    $uId = (int) $u['id'];
    $email = $_POST['email']?? '';
    $name = $_POST['name']?? '';
    $phone = $_POST['phone']?? '';
    
    $db->query("UPDATE users SET email = '$email' , name ='$name' , phone = '$phone' WHERE id = $uId ");

    $_SESSION['user']['name'] = trim($_POST['name']);
    $_SESSION['user']['email'] = trim($_POST['email']);
    $_SESSION['user']['phone'] = trim($_POST['phone']);

    go('profile.php' , 'อัพเดทโปรไฟล์');
    
}
if(isset($_POST['change'])){
    $uId = (int) $u['id'];
    $password = $_POST['password']?? '';


    $ph = password_hash($password , PASSWORD_DEFAULT);
    $passSafe = $db->real_escape_string($ph);
    if(!$db->query("UPDATE users SET password = '$passSafe' WHERE id = $uId")){
        go('profile.php' , 'รหัสผ่านไม่ถุกต้อง' , 'danger');
    }
    go('profile.php' , 'สำเร็จ');
}

