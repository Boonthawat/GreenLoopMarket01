<?php
require '../init.php';
require '../controller/marketplace_admin.php';
head('จัดการร้านค้า');
?>
<h1 class="h2 text-success">GreenLoop Marketplace</h1>
<p>รอตรวจสอบ <?= (int) $metrics['pending_orders']?> รายการ · ยอดขายที่อนุมัติแล้ว ฿<?= number_format((float) $metrics['sales'], 2)?> · แต้มที่ใช้ <?= number_format((int) $metrics['points'])?> · สินค้าใกล้หมด <?= (int) $metrics['low_stock']?></p>
<h2 class="h4 mt-4">สินค้า</h2>
<table class="table"><tr><th>ชื่อ</th><th>ราคา</th><th>คงเหลือ</th><th>สถานะ</th></tr>
<?php foreach($products as $p): ?><tr><td><?= e($p['name'])?></td><td>฿<?= number_format((float) $p['price_thb'], 2)?></td><td><?= (int) $p['stock']?></td><td><?= $p['is_active'] ? 'เปิดขาย' : 'ปิดขาย'?></td></tr><?php endforeach; ?>
</table>
<h2 class="h4 mt-4">คำสั่งซื้อ</h2>
<table class="table"><tr><th>เลขที่</th><th>สมาชิก</th><th>สินค้า</th><th>ที่อยู่จัดส่ง</th><th>ยอด</th><th>สลิป</th><th>สถานะ</th><th>ตรวจสอบ</th></tr>
<?php foreach($orders as $o): ?>
    <?php $statusLabels = ['pending_payment' => 'รอตรวจสลิป', 'processing' => 'กำลังดำเนินการ', 'shipped' => 'จัดส่งแล้ว', 'delivered' => 'ส่งถึงแล้ว', 'payment_rejected' => 'สลิปไม่ผ่าน']; ?>
    <tr>
        <td><?= e($o['order_number'])?></td>
        <td><?= e($o['name'])?></td>
        <td><?= e($o['item_list'] ?? '')?></td>
        <td><?= nl2br(e($o['shipping_address']))?></td>
        <td>฿<?= number_format((float) $o['subtotal'] - (float) $o['discount_amount'], 2)?>
            <?php if((int) $o['points_used'] > 0): ?><small class="d-block text-muted">ใช้ <?= (int) $o['points_used']?> แต้ม ลด ฿<?= number_format((float) $o['discount_amount'], 2)?></small><?php endif; ?>
        </td>
        <td><?php if($o['payment_slip_path']): ?><a href="<?= e($o['payment_slip_path'])?>" target="_blank" rel="noopener">ดูสลิป</a><?php else: ?>-<?php endif; ?></td>
        <td><?= e($statusLabels[$o['status']] ?? $o['status'])?></td>
        <td>
            <?php if($o['status'] === 'pending_payment'): ?>
                <form method="post" class="d-flex gap-1">
                    <input type="hidden" name="csrf_token" value="<?= e($adminOrderCsrf)?>">
                    <input type="hidden" name="order_id" value="<?= (int) $o['id']?>">
                    <button class="btn btn-sm btn-success" type="submit" name="order_action" value="approve">อนุมัติคำสั่งซื้อ</button>
                    <button class="btn btn-sm btn-danger" type="submit" name="order_action" value="reject" onclick="return confirm('ปฏิเสธสลิปและคืนสินค้าเข้าสต็อกหรือไม่?')">ปฏิเสธ</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php foot() ?>
