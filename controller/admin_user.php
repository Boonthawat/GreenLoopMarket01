<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');

if(isset($_POST['sus'])){
    $id = $_POST['id'];
    if($id == 1){
        $db->query("UPDATE users  SET status = 'suspended' WHERE role = 'user' AND id = $id");
        go('user.php' , 'ระงับสิทธิ์' , 'danger');
    } 


}

// var_dump($_POST['unsus']);exit;
if(isset($_POST['unsus'])){
    $id2 = $_POST['2']; 

    if($id2 == 2){
        $db->query("UPDATE users  SET status = 'active' WHERE role = 'user' AND id = $id2");
   go('user.php' , 'ยกเลิกระงับสิทธิ์');

    }
}
    

$row = $db->query("SELECT name,phone,points,status,id FROM users WHERE role = 'user' ORDER BY id DESC ")->fetch_all(MYSQLI_ASSOC);