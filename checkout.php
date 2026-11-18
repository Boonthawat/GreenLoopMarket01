<?php
require 'init.php';
require 'controller/checkout.php';

head('ยืนยันการซื้อ', false, $walletBalance);
?>
<section class="card card-body">
    <h1 class="h3 text-success">ยืนยันการซื้อ</h1>
    <h2 class="h5"><?= e($product['name'])?></h2>
    <p>ราคาชิ้นละ ฿<?= number_format((float) $product['price_thb'], 2)?> · คงเหลือ <?= (int) $product['stock']?> ชิ้น</p>
    <p>ยอด Wallet ปัจจุบัน ฿<?= number_format((float) $walletBalance, 2)?></p>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken)?>">
        <input type="hidden" name="product_id" value="<?= (int) $product['id']?>">
        <label class="form-label" for="quantity">จำนวน</label>
        <input id="quantity" class="form-control mb-3" type="number" name="quantity" min="1" max="<?= min(99, (int) $product['stock'])?>" value="1" required>
        <label class="form-label" for="shipping_address">ที่อยู่จัดส่ง</label>
        <textarea id="shipping_address" class="form-control mb-3" name="shipping_address" rows="3" minlength="5" maxlength="500" required></textarea>
        <button class="btn btn-success" type="submit" name="buy_now" <?= $product['stock'] < 1 ? 'disabled' : ''?>>ยืนยันและหักเงิน</button>
        <a class="btn btn-outline-secondary" href="shop.php">ยกเลิก</a>
    </form>
</section>
<?php foot(); ?>
