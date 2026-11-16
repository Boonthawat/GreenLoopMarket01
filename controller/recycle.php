<?php
require_once __DIR__ . '/../init.php';

$u = me();
if(!$u) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');


$uId = (int) $u['id'];
$cats = $db->query("SELECT * FROM categories ORDER BY id DESC");

if($_POST){
    $item = $_POST['items_name'];
    $delivery = $_POST['delivery_type'];
    $add = $_POST['address'];
    $weight = $_POST['weight_kg'];

   $image = upload('image');
    $db->query("INSERT INTO  recycles (item_name , delivery_type , address , weight_kg , user_id , image) VALUES ('$item' , '$delivery' , '$add' , $weight  , $uId , '$image')");

    go('recycle.php#recycle-form' , 'ส่งคำขอ');
}


