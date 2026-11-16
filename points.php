 <?php
 require 'init.php';
require 'controller/points.php';
head("แต้มของฉัน");
$medals = ['🏆','🎖️','🥇','🥈','🥉'];
$points = (int)  $u['points'];
$currentLevel = $points >= 600 ? '🌍 Net Zero Hero' :
    ($points >= 300 ? '🌳 ต้นไม้' :
    ($points >= 100 ? '🌱 ต้นกล้า' :  
        '🙂 มือใหม่'
    ));
?>
<div class="hero mb-4">
    <div class="text-center">
        <h2 class="fs-1">🫂</h2>
        <p class="fs-1"><?= $currentLevel?></p>
        <p class="fs-2">สะสมแล้ว <?= $points?> แต้ม</p>
    </div>
</div>
    <div class="row g-3 justify-content-center">
        <div class="col-lg-3 text-center">
            <div class="card card-body h-100 p-4">
                <span class="text-muted">♻️ จำนวนครั้งที่รีไซเคิล</span>
                <strong class="text-success"><?= number_format($sumRecycles['n'])?> ครั้ง</strong>
                
            </div>
        </div>
        <div class="col-lg-3  text-center">
            <div class="card card-body h-100 p-4">
                <span class="text-muted">📦 กิโลกรัมที่รีไซเคิล</span>
                <strong class="text-success"><?= number_format($sumRecycles['kg'])?> กก.</strong>
            </div>
        </div>
        <div class="col-lg-3  text-center">
            <div class="card card-body h-100 p-4">
                <span class="text-muted">🌍 ช่วยโลกไปแล้ว</span>
                <strong class="text-success"><?= number_format($sumRecycles['co2'])?> kgCO2e</strong>
            </div>
        </div>
    </div>
<div class="card card-body mt-5">
    <table id="points_table" class="table table-stripped bg-white">
        <thead>
            <tr>
                <th>อันดับ</th>
                <th>ชื่อ</th>
                <!-- <th>ระดับ</th> -->
                <th class="text-end">แต้ม</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($topUser as $i => $t): ?>
                <?php $memberlevel = match(true){
                    $t['points'] >= 600 => '🌍 Net Zero Hero',
                    $t['points'] >= 300 => '🌳 ต้นไม้',
                    $t['points'] >= 100 => '🌱 ต้นกล้า',
                    default => '🙂 มือใหม่'
                }
                ?>
                <tr class="<?= $t['id'] == $u['id'] ? 'table-success': '' ?>">
                    <td><?= $medals[$i]?></td>
                    <td><?= $t['name']?></td>
                    <td class="text-end"><?= $t['points']?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php foot() ?>
<script>
    $(document).ready(function(){
        const table = $('#points_table').DataTable();
    });
</script>
