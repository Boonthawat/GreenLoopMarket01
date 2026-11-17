<?php
require 'init.php';
require 'lib/marketplace.php';
require 'controller/shop.php';
head('ร้านค้า GreenLoop', false, $walletBalance);
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="h2 text-success">สินค้ารักษ์โลก</h1><p class="text-muted mb-0">เลือกสินค้าแล้วใช้แต้มเป็นส่วนลดได้</p></div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cartModal">🛒 ตะกร้า (<?= (int) $cartCount?>)</button>
</div>
<div class="row g-3">
    <?php foreach($products as $product): ?>
        <div class="col-sm-6 col-lg-4">
            <article class="card h-100 p-3">
                <?php if($product['image_path']): ?><img src="<?= e($product['image_path'])?>" class="w-100 rounded mb-3" style="height:180px;object-fit:cover" alt="<?= e($product['name'])?>"><?php endif; ?>
                <h2 class="h5"><?= e($product['name'])?></h2>
                <p class="small text-muted"><?= e($product['description'])?></p>
                <strong class="text-success mb-2">฿<?= number_format((float) $product['price_thb'], 2)?></strong>
                <form method="post" class="mt-auto">
                    <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id']?>">
                    <button class="btn btn-success w-100" name="add_to_cart" <?= $product['stock'] < 1 ? 'disabled' : ''?>>เพิ่มลงตะกร้า</button>
                </form>
            </article>
        </div>
    <?php endforeach; ?>
    <?php if(!$products): ?><div class="col-12"><div class="alert alert-secondary">ยังไม่มีสินค้าในร้านค้า</div></div><?php endif; ?>
</div>
<?php require 'views/shop_cart_modal.php'; foot(); ?>
