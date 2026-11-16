 <?php
 require_once __DIR__ . '/../controller/admin_notifications.php';

head("แต้มของฉัน");
?>

<div class="container py-4">
    <?php foreach($rowNoti as $r): ?>
        <div class="card card-body mx-auto">
            <b><?= $r['title']?></b>
            <br>
                <?= $r['messege'] ?>
            <br>
            <span><?= $r['created_at'] ? date('d/m/Y H:i') : ''?></span>
        </div>
    <?php endforeach; ?>
    <?php if (!$rowNoti): ?>
        <div class="alert alert-secondary">ยังไม่มีการแจ้งเตือนในตอนนี้</div>
    <?php endif; ?>
</div>