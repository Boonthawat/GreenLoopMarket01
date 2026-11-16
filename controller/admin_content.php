<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');


if(isset($_POST['delete'])){
    $uId = (int) $_POST['id'];
    $db->query("DELETE FROM contents WHERE id = $uId");
    go('content.php' , 'ลบ' , 'danger');
}

if($_POST){
     $uId = (int) $_POST['id'];
     $startAt = $_POST['start_at'];
     $endAt = $_POST['end_at'];
     $title = $_POST['title'];
     $body = $_POST['body'];

     if ($uId !== ''){
         $db->query("INSERT INTO contents (title , body , start_at , end_at , user_id ) VALUES ('$title' , '$body' ,' $startAt' , '$endAt' , $uId)");
        } else{
             $db->query("UPDATE contents SET title = '$title' , body = '$body' , start_at = $startAt , end_at = $endAt WHERE id = $uId"); 
     }
     go('content.php' , 'บันทึก' );

}

$edit = [
    'id' => '',
    'title' => '',
    'body' => '',
    'start_at' => '',
    'end_at' => ''
];
$rows = $db->query("SELECT * FROM contents ORDER BY start_at DESC")->fetch_all(MYSQLI_ASSOC);

$local = function($q){
    return $q ? date('Y-m-d H:i' , strtotime($q)): null;
}
?>

