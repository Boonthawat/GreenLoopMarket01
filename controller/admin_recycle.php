<?php
require_once __DIR__ . '/../init.php';

$admin = me();
if(!$admin) go('../login.php' , 'เข้าสู่ระบบก่อน' , 'danger');
if($admin['role'] !== 'admin') go('../index.php' , 'สำหรับแอดมิน' , 'danger');
 
if(isset($_POST['reject'])){
    $rId = $_POST['id'];
    $db->query("UPDATE recycles SET status = 'rejected' WHERE id = $rId");
    go('recycle.php' , 'ปฏิเสธ' , 'danger');
}
if(isset($_POST['approved'])){
    $rId = $_POST['id'];
    $r = $db->query("SELECT r.*,r.user_id, c.co2_per_kg FROM recycles r JOIN categories c  WHERE status = 'pending'")->fetch_assoc();

    $points = round($r['weight_kg'] * 20);
    $co2 = $r['weight_kg'] * $r['co2_per_kg'];
    $userId = (int) $r['user_id'];
    // var_dump($userId); exit;



    $db->query("UPDATE recycles SET points = $points , co2_kg = $co2 , status = 'done' WHERE id = $rId");
    $db->query("UPDATE users SET points = points + $points WHERE  id = $userId");
    $db->query("INSERT INTO point_transactions (user_id , amount , related_id , related_type , link) VALUES ($userId , $points , $rId , 'รีไซเคิล' , 'recycle.php')");

    go('recycle.php' , "ตรวจรับ");

}

$row = $db->query("SELECT r.id , r.delivery_type , r.address , r.weight_kg , r.image , r.item_name ,r.status , u.name AS member FROM recycles r 
JOIN users u ON u.id = r.user_id ORDER BY r.id DESC")->fetch_all(MYSQLI_ASSOC);


// var_dump($row);exit;

?>