<?php
    require 'init.php';
    if(!me()) go('login.php');
    require 'controller/home.php';
    head('หน้าแรก', false);
    $u = me();

    $steps = [
        ['📦','1. เลือกรายการ','ดูขยะอิเล็กทรอนิกส์ที่ต้องการรีไซเคิล','recycle.php#form-recycle'],
        ['🔎','2. ค้นหา','เลือกหมวดหมู่เพื่อรีไซเคิลและส่งมอบอย่างถูกวิธี','recycle.php'],
        ['🏆','3. แต้มแลกของ','รีไซเคิลเพื่อรับแต้มแและได้รับของรางวัล','points.php'],
    ];

    // $coupon = [
    //     ['☂️','100 แต้ม','แต้มร่มกันฝน','points.php'],
    //     ['🎟️','50 แต้ม','คูปองส่วนลด','points.php'],
    //     ['🥛','600 แต้ม','แก้วน้ำเก็บความเย็น','points.php'],
    // ];
    $slides = [
        ['assets\images\1.2-Information-Technology.webp'],
        ['assets\images\3uZZxwDJCQGCTQMkXJ4f.webp'],
        ['assets\images\4-5-1024x576.jpg'],
    ];
?>
<!-- <div class="hero mb-4">
    <?php if($u): ?>
        <h1>สวัสดี <?= $u['name']?> 👋</h1>
        <p>สะสมแล้ว <?= $u['points']?> แต้ม</p>
        <a href="recycle.php" class="btn btn-light">♻️ เริ่มรีไซเคิลเลย</a>
        <a href="points.php" class="btn btn-outline-light">🏆 ดูแต้มสะสม</a>
    <?php else: ?>
        <h1>เว็บไซต์สำหรับการรีไซเคิลอุปกรณ์ อิเล็กทรอนิส์ อย่างถูกวิธี</h1>
        <p>กำจัดของที่ไม่ได้แล้วอย่างถูกต้องเพื่อโลกที่ยั่งยืน</p>
        <a href="register.php" class="btn btn-light">สมัครสมาฃิกฟรี</a>
        <a href="login.php" class="btn btn-outline-light">เข้าสู่ระบบ</a>
    <?php endif; ?>
</div> -->
<div id="homeCarousel" class="carousel slide carousel-fade mb-5 shadow"
     style="border-radius:1.5rem;overflow:hidden"
     data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">

    <div class="carousel-indicators">
        <?php foreach($slides as $i => $s): ?>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="<?= $i ?>"
                    class="<?= $i === 0 ? 'active' : '' ?>"></button>
        <?php endforeach; ?>
    </div>

    <div class="carousel-inner" style="border-radius:1.5rem;overflow:hidden">
        <?php foreach($slides as $i => $s): ?>
            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" style="background:#1f5c35">
                <img src="<?= $s[0] ?>" class="d-block w-100" style="height:420px;object-fit:cover" alt="">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,.6)"></div>

                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-center text-white px-4">
                    <?php if($u): ?>
                        <h1 class="fw-bold fs-0"style="font-size: 8rem;">GREENLOOP</h1>
                        <h2 class="fw-bold">สวัสดี <?= $u['name']?> 👋</h2>
                        <p class="fs-5">สะสมแล้ว <?= $u['points']?> แต้ม</p>
                        <div>
                            <a href="recycle.php" class="btn btn-light">♻️ เริ่มรีไซเคิลเลย</a>
                            <a href="shop.php" class="btn btn-light">🏪 ซื้อสินค้า</a>
                            <a href="points.php" class="btn btn-light">🏆 ดูแต้มสะสม</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<section class="home-impact-section">
    <div class="home-section-heading mt-2 mb-4">
        <span class="section-eyebrow text-success fw-bold mb-2">SMALL ACTION, BIG IMPACT</span>
        <h2 class="mt-2">ตอนนี้มีคนกำลังรอช่วยอยู่ มาร่วมด้วยช่วยกันเพื่อโลกที่สะอาด</h2>
        <small></small>
    </div>
    <div class="row g-3 home-impact-grid">
        <div class="col-6 col-xl-3">
            <div class="card card-body p-4 h-100 bg-white">
                <span class="impact-logo fs-3">🫂</span>
                <span class="impact-label">ผู้ร่วมกันรักโลก</span>
                <strong class="d-block text-success fs-3"><?= number_format($MC)?></strong>
                <small>สมาชิก GreenLoop</small>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card card-body p-4 h-100 bg-white">
                <span class="impact-logo">♻️</span>
                <span class="impact-label">รีไซเคิลไปแล้ว</span>
                <strong class="d-block text-success fs-3"><?= number_format($sumRecycles['n'])?></strong>
                <small>จำนวนครั้งที่รีไซเคิล</small>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card card-body p-4 h-100 bg-white">
                <span class="impact-logo">📦</span>
                <span class="impact-label">E-Waste ทั้งหมด</span>
                <strong class="d-block text-success fs-3"><?= number_format($sumRecycles['kg'])?></strong>
                <small>กิโลกรัม</small>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card card-body p-4 h-100 impact-card-highlight">
                <span class="impact-logo">🌍</span>
                <span class="impact-label">ชวยโลกไปแล้ว</span>
                <strong class="d-block text-success fs-3"><?= number_format($sumRecycles['co2'])?></strong>
                <small>kgCO2e ต่อ กก.</small>
            </div>
        </div>
    </div>
    <div class="impact-footnote text-success">สรุปยอดหลังจากที่ผู้ดูแลตรวจสอบแล้ว</div>
</section>
<section class="home-journy-section">
    <div class="home-section-heading mt-5 mb-2">
        <span class="section-eyebrow"></span>
        <h2 class="">วิธีการรีไซเคิลในเว็บไซต์นี้</h2>
        <small></small>
    </div>
    <div class="mt-2 mb-2 row g-3">
        <?php foreach($steps as $index => $s): ?>
            <div class="col-md-4">
                <a href="<?= $s[3]?>" class="card journy-card h-100 p-4 text-decoration-none">
                    <div class="journy-card-tops">
                        <span class="journy-logo"><?= $s[0]?></span>
                        <span class="journy-number"></span>
                    </div>
                    <h3><?= $s[1]?></h3>
                    <small><?= $s[2]?></small>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<section class="mt-5 mb-4">
    <div class="home-campaign bg-success text-white rounded-4 shadow p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="text-start">
            <span class="badge bg-light text-success mb-2">มาร่วมกันช่วยโลก</span>
            <h3 class="fw-bold">ในลิ้นชักของคุณอาจมี "ก้าวเล็กๆ" ซ่อนอยู่</h3>
            <p class="mb-0 opacity-75">โทรศัพท์ สายชาร์จที่ไม่ได้ใช้แล้วไม่ควรทิ้งรวมกัน ควรแยกและทิ้งอย่างถูกวิธีเพื่อช่วยโลกให้ยั่งยืนต่อไป</p>
        </div>
        <a href="recycle.php" class="btn btn-light btn-lg flex-shrink-0">เริ่มรีไซเคิล ♻️</a>
    </div>
</section>
<!-- <section class="home-journy-section">
    <div class="home-section-heading mt-5 mb-4">
        <span></span>
        <h2>สะสมแต้มตามเงื่อนไขครบเพื่อรับของรางวัล</h2>
        <small></small>
    </div>
    <div class="row g-3">
        <?php foreach($coupon as $index => $c): ?>
            <div class="col-md-4">
                <a href="<?= $c[3]?>" class="card journy-card h-100 p-4 text-decoration-none">
                    <div class="journy-card-tops">
                        <span class="journy-logo fs-1"><?= $c[0]?></span>
                        <span class="journy-number"></span>
                    </div>
                    <h3><?= $c[1]?></h3>
                    <small><?= $c[2]?></small>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section> -->
<section class="article-section">
    <div class="article-heading mt-5 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <span></span>
            <h4 class="fw-bold">ข่าวสารหน้ารู้เกี่ยวกับ E-waste</h4>
            <small></small>
        </div>
        <div class="article-count"><?= $FN ? count($newContents) + 1 : 0?> เรื่อง</div>
    </div>
    <?php if($FN): ?>
        <article class="card card-body article-feature d-flex justiy-content-between flex-direction-column p-4">
            <div class="article-feature-content text-align-center">
                <span class="article-label">เรื่องเด่น</span>
                <h3><?= $FN['title']?></h3>
                <p><?= $FN['body']?></p>
                <small class="text-muted"><?= $FN['start_at'] ? date('d/m/Y H:i',strtotime($FN['start_at'])) : ''?></small>
            </div>
        </article>
        <div class="row">
        <?php foreach ($newContents as $index => $n): ?>
            <div class="col-lg-4 mt-4 mb-2">
                    <article class="card card-body h-100 py-4">
                        <div class="article-top d-flex justify-content-between">
                            <span>🌍</span>
                            <small class="text-muted"><?= $n['start_at'] ? date('d/m/Y H:i',strtotime($n['start_at'])) : ''?></small>
                        </div>
                        <h4><?= nl2br($n['title'])?></h4>
                        <p><?= nl2br($n['body'])?></p>
                        <small class="text-muted">GREENLOOP JOURNAL</small>
                    </article>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="alert alert-secondary">ยังไม่มีข่าวสารในตอนนี้</div>
    <?php endif; ?>
</section>
<?php foot() ?>