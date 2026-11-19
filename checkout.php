<?php
require 'init.php';
require 'controller/checkout.php';

head('ยืนยันการซื้อ', false);
?>
<section class="card card-body">
    <h1 class="h3 text-success">ชำระเงินด้วย PromptPay</h1>
    <h2 class="h5"><?= e($product['name'])?></h2>
    <p>ราคาชิ้นละ ฿<?= number_format((float) $product['price_thb'], 2)?> · คงเหลือ <?= (int) $product['stock']?> ชิ้น</p>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken)?>">
        <input type="hidden" name="product_id" value="<?= (int) $product['id']?>">
    <label class="form-label" for="quantity">จำนวน</label>
    <input id="quantity" class="form-control mb-3" type="number" name="quantity" min="1" max="<?= min(99, (int) $product['stock'])?>" value="1" required>
    <label class="form-label" for="points_to_use">ใช้ GreenPoint (คุณมี <?= number_format($userPoints)?> แต้ม)</label>
    <input id="points_to_use" class="form-control mb-2" type="number" name="points_to_use" min="0" max="<?= $maxRedeemPoints?>" step="10" value="0">
    <small class="text-muted">ใช้ 10 แต้ม ลด ฿1 สูงสุด <?= number_format($maxRedeemPoints)?> แต้มสำหรับรายการนี้</small>
    <p class="mt-3 mb-1">ยอดก่อนลด: ฿<span id="subtotal"><?= number_format((float) $product['price_thb'], 2)?></span></p>
    <p class="mb-1">ส่วนลดจากแต้ม: ฿<span id="point-discount">0.00</span></p>
    <p>ยอดที่ต้องชำระ: ฿<strong id="payment-total"><?= number_format((float) $product['price_thb'], 2)?></strong></p>
    <div id="payment-instructions" class="border rounded p-3 mb-3 text-center">
        <?php if($paymentQrAvailable): ?>
            <img src="<?= e($paymentQrPath)?>" alt="PromptPay QR" class="img-fluid" style="max-width: 260px">
            <p class="mb-0">สแกน QR และโอนยอดตามจำนวนที่สั่งซื้อ</p>
        <?php else: ?>
            <p class="text-warning mb-0">ยังไม่ได้ตั้งค่า QR รับเงิน กรุณาวางภาพ PromptPay ที่ <code>assets/images/promptpay-qr.png</code></p>
        <?php endif; ?>
    </div>
        <label class="form-label" for="shipping_address">ที่อยู่จัดส่ง</label>
        <textarea id="shipping_address" class="form-control mb-3" name="shipping_address" rows="3" minlength="5" maxlength="500" required></textarea>
        <label class="form-label" for="payment_slip">แนบสลิปการโอน</label>
        <input id="payment_slip" class="form-control mb-3" type="file" name="payment_slip" accept=".jpg,.jpeg,.png,.webp">
        <small class="text-muted">รองรับ JPG, PNG, WEBP ขนาดไม่เกิน 5 MB ระบบจะส่งคำสั่งซื้อให้แอดมินตรวจสอบก่อนยืนยัน</small>
        <div>
            <button id="submit-order" class="btn btn-success mt-3" type="submit" name="buy_now" <?= $product['stock'] < 1 ? 'disabled' : ''?>>ส่งคำสั่งซื้อและสลิป</button>
            <a class="btn btn-outline-secondary" href="shop.php">ยกเลิก</a>
        </div>
    </form>
</section>
<script>
    const quantityInput = document.getElementById('quantity');
    const pointsInput = document.getElementById('points_to_use');
    const paymentTotal = document.getElementById('payment-total');
    const subtotalDisplay = document.getElementById('subtotal');
    const discountDisplay = document.getElementById('point-discount');
    const paymentSlip = document.getElementById('payment_slip');
    const paymentInstructions = document.getElementById('payment-instructions');
    const submitOrder = document.getElementById('submit-order');
    const unitPriceCents = <?= (int) round((float) $product['price_thb'] * 100)?>;
    const availablePoints = <?= $userPoints?>;
    const maxQuantity = <?= min(99, (int) $product['stock'])?>;
    const formatBaht = new Intl.NumberFormat('th-TH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const updatePayment = () => {
        const quantity = Math.min(maxQuantity, Math.max(1, parseInt(quantityInput.value, 10) || 1));
        quantityInput.value = quantity;
        const subtotalCents = unitPriceCents * quantity;
        const maxPoints = Math.min(Math.floor(availablePoints / 10) * 10, Math.floor(subtotalCents / 100) * 10);
        pointsInput.max = maxPoints;
        let points = Math.max(0, parseInt(pointsInput.value, 10) || 0);
        if (points > maxPoints) {
            points = maxPoints;
            pointsInput.value = points;
        }
        const discountCents = Math.floor(points / 10) * 100;
        const dueCents = Math.max(0, subtotalCents - discountCents);
        subtotalDisplay.textContent = formatBaht.format(subtotalCents / 100);
        discountDisplay.textContent = formatBaht.format(discountCents / 100);
        paymentTotal.textContent = formatBaht.format(dueCents / 100);
        paymentSlip.required = dueCents > 0;
        paymentSlip.disabled = dueCents === 0;
        paymentInstructions.hidden = dueCents === 0;
        submitOrder.textContent = dueCents > 0 ? 'ส่งคำสั่งซื้อและสลิป' : 'ยืนยันคำสั่งซื้อโดยใช้แต้ม';
    };
    quantityInput.addEventListener('input', updatePayment);
    pointsInput.addEventListener('input', updatePayment);
    updatePayment();
</script>
<?php foot(); ?>
