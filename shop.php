<?php
require 'init.php';
require 'lib/marketplace.php';
require 'controller/shop.php';

head('ร้านค้า GreenLoop', false, $walletBalance);
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <span class="badge bg-success mb-2">SHOP SUSTAINABLY</span>
        <h1 class="h2 text-success fw-bold mb-1">สินค้ารักษ์โลก</h1>
        <p class="text-muted mb-0">เลือกสินค้าที่เป็นมิตรต่อโลก แล้วใช้ GreenPoints เป็นส่วนลดได้ตอนชำระเงิน</p>
    </div>
    <a href="checkout.php" class="btn btn-success">🛒 ตะกร้า (<?= (int) $cartCount?>)</a>
</div>
<div class="row g-4">
    <?php foreach($products as $product): ?>
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <article class="card h-100 overflow-hidden">
                <div class="ratio ratio-4x3 bg-light">
                    <?php if($product['image_path']): ?>
                        <img src="<?= e($product['image_path'])?>" class="w-100 h-100" style="object-fit: cover" alt="<?= e($product['name'])?>">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center fs-1" aria-hidden="true">🌱</div>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between gap-2">
                        <h2 class="h5 text-success"><?= e($product['name'])?></h2>
                        <span class="badge <?= $product['stock'] > 0 ? 'bg-success' : 'bg-secondary'?> align-self-start">
                            <?= $product['stock'] > 0 ? 'เหลือ ' . (int) $product['stock'] : 'สินค้าหมด'?>
                        </span>
                    </div>
                    <p class="text-muted small"><?= e($product['description'])?></p>
                    <p class="small mb-2"><strong>🌍 ผลกระทบ:</strong> <?= e($product['eco_impact'])?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <strong class="fs-5 text-success">฿<?= number_format((float) $product['price_thb'], 2)?></strong>
                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                            <input type="hidden" name="product_id" value="<?= (int) $product['id']?>">
                            <button class="btn btn-success btn-sm" name="add_to_cart" type="submit" <?= $product['stock'] < 1 ? 'disabled' : ''?>>เพิ่มลงตะกร้า</button>
                        </form>
                    </div>
                </div>
            </article>
        </div>
    <?php endforeach; ?>
    <?php if(!$products): ?>
        <div class="col-12"><div class="alert alert-secondary">ยังไม่มีสินค้าในร้านค้า</div></div>
    <?php endif; ?>
</div>
<?php foot() ?>
