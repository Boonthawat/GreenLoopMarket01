<?php
require '../init.php';
require '../controller/marketplace_admin.php';
head('จัดการร้านค้า');
?>
<h1 class="h2 text-success">GreenLoop Marketplace</h1>
<h2 class="h4 mt-4">คำสั่งซื้อรออนุมัติ</h2>
<table class="table"><tr><th>เลขที่</th><th>สมาชิก</th><th>สินค้า</th><th>ที่อยู่จัดส่ง</th><th>ยอด</th><th>สลิป</th><th>อนุมัติ</th></tr>
<?php if (!$orders): ?><tr><td colspan="7">ไม่มีคำสั่งซื้อรออนุมัติ</td></tr><?php endif; ?>
<?php foreach($orders as $o): ?>
    <tr>
        <td><?= e($o['order_number'])?></td>
        <td><?= e($o['name'])?></td>
        <td><?= e($o['item_list'] ?? '')?></td>
        <td><?= nl2br(e($o['shipping_address']))?></td>
        <td>฿<?= number_format((float) $o['subtotal'] - (float) $o['discount_amount'], 2)?>
            <?php if((int) $o['points_used'] > 0): ?><small class="d-block text-muted">ใช้ <?= (int) $o['points_used']?> แต้ม ลด ฿<?= number_format((float) $o['discount_amount'], 2)?></small><?php endif; ?>
        </td>
        <td>
            <?php if($o['payment_slip_path']): ?>
                <a href="../<?= e($o['payment_slip_path'])?>" target="_blank" rel="noopener">
                    <img src="../<?= e($o['payment_slip_path'])?>" alt="สลิปชำระเงิน" style="max-width:90px; max-height:70px; object-fit:cover;">
                </a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
        <td>
            <form method="post">
                <input type="hidden" name="order_id" value="<?= (int) $o['id']?>">
                <button class="btn btn-sm btn-success" type="submit" name="order_action" value="approve">อนุมัติคำสั่งซื้อ</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php foot() ?>
