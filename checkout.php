<?php
require 'init.php';
require 'lib/marketplace.php';
require 'controller/checkout.php';

head('ตะกร้าสินค้าและชำระเงิน', true, $balance);
?>

<?php if($order): ?>
    <div class="card card-body border-success mb-4">
        <h2 class="text-success">✅ สั่งซื้อเรียบร้อย</h2>
        <p>เลขที่คำสั่งซื้อ <strong><?= e($order['order_number'])?></strong></p>
        <p>ใช้ <?= number_format((int) $order['points_used'])?> GreenPoints ลด ฿<?= number_format((float) $order['discount_amount'], 2)?></p>
        <p class="mb-0">ชำระจาก Wallet ฿<?= number_format((float) $order['wallet_paid'], 2)?></p>
    </div>
    <div class="card card-body mb-4">
        <h3 class="h5">รายการสินค้า</h3>
        <?php foreach($orderItems as $item): ?>
            <div class="d-flex justify-content-between border-bottom py-2">
                <span><?= e($item['product_name'])?> × <?= (int) $item['quantity']?></span>
                <span>฿<?= number_format((float) $item['unit_price'] * (int) $item['quantity'], 2)?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="shop.php" class="btn btn-success">เลือกซื้อสินค้าต่อ</a>
<?php else: ?>
    <h1 class="h2 text-success mb-4">ตะกร้าสินค้า</h1>
    <?php if(!$cart): ?>
        <div class="alert alert-info">ยังไม่มีสินค้าในตะกร้า <a href="shop.php">ไปเลือกสินค้า</a></div>
    <?php else: ?>
        <form method="post" class="card card-body mb-4">
            <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>สินค้า</th><th>ราคา</th><th>จำนวน</th><th>รวม</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach($cart as $id => $quantity): ?>
                            <?php $product = $products[(int) $id] ?? null; ?>
                            <tr>
                                <td>
                                    <?= $product ? e($product['name']) : 'สินค้านี้ไม่มีแล้ว'?>
                                    <?php if($product && (!$product['is_active'] || $quantity > $product['stock'])): ?>
                                        <div class="text-danger small">สต็อกไม่เพียงพอหรือปิดขายแล้ว</div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $product ? '฿' . number_format((float) $product['price_thb'], 2) : '—'?></td>
                                <td><input class="form-control" type="number" min="0" max="99" name="quantity[<?= (int) $id?>]" value="<?= (int) $quantity?>" style="max-width: 100px"></td>
                                <td><?= $product ? '฿' . number_format((float) $product['price_thb'] * (int) $quantity, 2) : '—'?></td>
                                <td><button class="btn btn-outline-danger btn-sm" type="submit" name="remove_id" value="<?= (int) $id?>">ลบ</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-outline-success align-self-start" type="submit" name="update_cart">อัปเดตตะกร้า</button>
        </form>

        <div class="row g-4">
            <div class="col-lg-7">
                <form method="post" class="card card-body">
                    <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                    <h2 class="h4 text-success">จัดส่งและใช้ GreenPoints</h2>
                    <label class="form-label" for="shipping_address">ที่อยู่จัดส่ง</label>
                    <textarea id="shipping_address" name="shipping_address" class="form-control mb-3" rows="3" maxlength="500" required></textarea>
                    <label class="form-label" for="points_used">ใช้ GreenPoints: <strong><span id="pointsValue">0</span> คะแนน</strong></label>
                    <input id="points_used" name="points_used" class="form-range" type="range" min="0" max="<?= (int) $maxPoints?>" value="0" <?= $maxPoints < 1 ? 'disabled' : ''?>>
                    <small class="text-muted">10 GreenPoints = ฿1 · ใช้ส่วนลดได้ไม่เกิน 50% ของยอดสินค้า (สูงสุด <?= number_format($maxPoints)?> คะแนน)</small>
                    <div class="d-flex justify-content-between border-top mt-3 pt-3">
                        <span>ส่วนลด</span><strong id="discountValue">฿0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>ยอดชำระจาก Wallet</span><strong id="walletDue">฿<?= number_format($subtotalCents / 100, 2)?></strong>
                    </div>
                    <?php if(!$cartReady): ?>
                        <div class="alert alert-warning mt-3 mb-0">อัปเดตหรือลบสินค้าที่สต็อกไม่พอก่อนสั่งซื้อ</div>
                    <?php elseif((int) round((float) $balance * 100) < $subtotalCents - $maxPoints * 10): ?>
                        <div class="alert alert-warning mt-3 mb-0">ยอด Wallet อาจไม่พอสำหรับสินค้านี้ <a href="topup.php">เติมเงิน</a> หรือใช้ GreenPoints เพิ่ม</div>
                    <?php endif; ?>
                    <button class="btn btn-success mt-3" type="submit" name="place_order" <?= !$cartReady ? 'disabled' : ''?>>ยืนยันคำสั่งซื้อ</button>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="card card-body">
                    <h2 class="h4">สรุปคำสั่งซื้อ</h2>
                    <div class="d-flex justify-content-between"><span>ยอดสินค้า</span><strong>฿<?= number_format($subtotalCents / 100, 2)?></strong></div>
                    <div class="d-flex justify-content-between"><span>Wallet คงเหลือ</span><strong>฿<?= number_format((float) $balance, 2)?></strong></div>
                    <div class="d-flex justify-content-between"><span>GreenPoints</span><strong><?= number_format($availablePoints)?></strong></div>
                    <a href="topup.php" class="btn btn-outline-success mt-3">เติมเงินเข้า Wallet</a>
                </div>
            </div>
        </div>
        <script>
            const pointsSlider = document.getElementById('points_used');
            const pointsValue = document.getElementById('pointsValue');
            const discountValue = document.getElementById('discountValue');
            const walletDue = document.getElementById('walletDue');
            const subtotal = <?= $subtotalCents?>;
            const formatBaht = cents => new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB' }).format(cents / 100);
            if (pointsSlider) {
                pointsSlider.addEventListener('input', () => {
                    const points = Number(pointsSlider.value);
                    pointsValue.textContent = points.toLocaleString('th-TH');
                    discountValue.textContent = formatBaht(points * 10);
                    walletDue.textContent = formatBaht(subtotal - points * 10);
                });
            }
        </script>
    <?php endif; ?>
<?php endif; ?>
<?php foot() ?>
