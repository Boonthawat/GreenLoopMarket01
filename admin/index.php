 <?php
 require '../init.php';
require '../controller/admin_index.php';
head("แต้มของฉัน",false);
?>

<div class="text-left">
    <h2 class="fw-bold text-success mb-4">รายงาน DashBoard</h2>
    <button class="btn btn-success" onclick="window.print()">รายงานเนื้อหา</button>
    <p class=" fw-bold text-muted mt-4">รายงาน ณ วันที่  📅<?= date('d/m/y H:i')?></p>
</div>
<div class="row g-3 mt-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">👤 สมาชิกทั้งหมด</span>
            <h4 class="text-success mt-2"><?= number_format($MC)?> คน</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">🚫 สมาชิกที่ถูกระงับ</span>
            <h4 class="text-success mt-2"><?= number_format($MCS)?> คน</h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">📰 เนื้อหาทั้งหมด</span>
            <h4 class="text-success mt-2"><?= $contentCount ?></h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">♻️ รีไซเคิลที่สำเร็จ</span>
            <h4 class="text-success mt-2"><?= number_format($reDone)?></h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">⌛ คำขอรีไซเคิล</span>
            <h4 class="text-success mt-2"><?= number_format($rePending)?></h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">🔕 การแจ้งเตือนที่ยังไม่ได้อ่าน</span>
            <h4 class="text-success mt-2"><?= number_format($notReadNoti)?></h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center h-100 p-4">
            <span class="text-muted">🌍 รีไซเคิลไปทั้งหมด</span>
            <h4 class="text-success mt-2"><?= count($sumRecycles)?></h4>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-body text-center p-4 h-100">
            <span class="text-muted">📃 เนื้อหาที่แสดงอยู่</span>
            <h4 class="text-success mt-2"><?= count($newContents)?></h4>
        </div>
    </div>
</div>