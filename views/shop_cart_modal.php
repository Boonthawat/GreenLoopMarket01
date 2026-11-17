<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h4 text-success" id="cartModalTitle"><?= $order ? 'สั่งซื้อเรียบร้อย' : 'ตะกร้าสินค้า'?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>
            <div class="modal-body">
                <?php if($order): ?>
                    <div class="alert alert-success">
                        เลขที่คำสั่งซื้อ <strong><?= e($order['order_number'])?></strong><br>
                        ใช้ <?= number_format((int) $order['points_used'])?> แต้ม ลด ฿<?= number_format((float) $order['discount_amount'], 2)?><br>
                        ชำระจาก Wallet ฿<?= number_format((float) $order['wallet_paid'], 2)?>
                    </div>
                    <?php foreach($orderItems as $item): ?>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span><?= e($item['product_name'])?> × <?= (int) $item['quantity']?></span>
                            <span>฿<?= number_format((float) $item['unit_price'] * (int) $item['quantity'], 2)?></span>
                        </div>
                    <?php endforeach; ?>
                <?php elseif(!$cart): ?>
                    <p class="mb-0">ยังไม่มีสินค้าในตะกร้า เลือกสินค้าแล้วเพิ่มลงตะกร้าได้เลย</p>
                <?php else: ?>
                    <form method="post" class="mb-4">
                        <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                        <?php foreach($cart as $id => $quantity): ?>
                            <?php $product = $cartProducts[(int) $id] ?? null; ?>
                            <div class="d-flex flex-wrap align-items-center gap-2 border-bottom py-2">
                                <span class="flex-grow-1"><?= $product ? e($product['name']) : 'สินค้านี้ไม่มีแล้ว'?></span>
                                <?php if($product): ?><span>฿<?= number_format((float) $product['price_thb'], 2)?></span><?php endif; ?>
                                <input class="form-control" type="number" min="0" max="99" name="quantity[<?= (int) $id?>]" value="<?= (int) $quantity?>" aria-label="จำนวน" style="max-width: 90px">
                                <button class="btn btn-outline-danger btn-sm" type="submit" name="remove_id" value="<?= (int) $id?>">ลบ</button>
                                <?php if($product && (!$product['is_active'] || $quantity > $product['stock'])): ?>
                                    <small class="text-danger w-100">สต็อกไม่พอหรือปิดขายแล้ว</small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <button class="btn btn-outline-success btn-sm mt-3" type="submit" name="update_cart">อัปเดตจำนวน</button>
                    </form>
                    <form method="post" class="card card-body">
                        <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                        <label class="form-label" for="shipping_address">ที่อยู่จัดส่ง</label>
                        <textarea id="shipping_address" name="shipping_address" class="form-control mb-3" rows="2" maxlength="500" required></textarea>
                        <label for="points_used">ใช้ GreenPoints (สูงสุด <?= number_format($maxPoints)?>)</label>
                        <input id="points_used" name="points_used" class="form-range" type="range" min="0" max="<?= (int) $maxPoints?>" value="0" <?= $maxPoints < 1 ? 'disabled' : ''?>>
                        <div>ส่วนลด <strong id="discountValue">฿0.00</strong> · ยอดชำระ <strong id="walletDue">฿<?= number_format($subtotalCents / 100, 2)?></strong></div>
                        <small class="text-muted">10 แต้ม = ฿1 (ใช้ส่วนลดได้ไม่เกินครึ่งหนึ่งของยอดสินค้า)</small>
                        <?php if(!$cartReady): ?>
                            <div class="alert alert-warning mt-2 mb-0">กรุณาแก้จำนวนสินค้าหรือลบรายการที่สต็อกไม่พอ</div>
                        <?php elseif((int) round((float) $balance * 100) < $subtotalCents - $maxPoints * 10): ?>
                            <div class="alert alert-warning mt-2 mb-0">ยอด Wallet อาจไม่พอ <a href="topup.php">เติมเงิน</a></div>
                        <?php endif; ?>
                        <button class="btn btn-success mt-3" type="submit" name="place_order" <?= !$cartReady ? 'disabled' : ''?>>ยืนยันสั่งซื้อ</button>
                    </form>
                    <script>
                        const pointsSlider = document.getElementById('points_used');
                        const subtotalCents = <?= $subtotalCents?>;
                        if (pointsSlider) pointsSlider.addEventListener('input', () => {
                            const points = Number(pointsSlider.value);
                            document.getElementById('discountValue').textContent = `฿${(points / 10).toFixed(2)}`;
                            document.getElementById('walletDue').textContent = `฿${((subtotalCents - points * 10) / 100).toFixed(2)}`;
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    const cartQuery = new URLSearchParams(window.location.search);
    if (cartQuery.get('cart') === '1' || cartQuery.has('order') || window.location.hash === '#cartModal') {
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }
</script>
