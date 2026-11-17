<?php
require '../init.php';
require '../controller/marketplace_admin.php';
head('จัดการร้านค้า');
?>
<h1 class="h2 text-success">GreenLoop Marketplace</h1>
<p>ยอดขาย ฿<?= number_format((float) $metrics['sales'], 2)?> · แต้มที่ใช้ <?= number_format((int) $metrics['points'])?> · เติมเงินรอตรวจ <?= (int) $metrics['pending_topups']?> · สินค้าใกล้หมด <?= (int) $metrics['low_stock']?></p>
<h2 class="h4 mt-4">สินค้า</h2>
<table class="table"><tr><th>ชื่อ</th><th>ราคา</th><th>คงเหลือ</th><th>สถานะ</th></tr>
<?php foreach($products as $p): ?><tr><td><?= e($p['name'])?></td><td>฿<?= number_format((float) $p['price_thb'], 2)?></td><td><?= (int) $p['stock']?></td><td><?= $p['is_active'] ? 'เปิดขาย' : 'ปิดขาย'?></td></tr><?php endforeach; ?>
</table>
<h2 class="h4 mt-4">รายการเติมเงิน</h2>
<table class="table"><tr><th>สมาชิก</th><th>ยอด</th><th>สถานะ</th><th>สลิป</th></tr>
<?php foreach($topups as $t): ?><tr><td><?= e($t['name'])?> (<?= e($t['email'])?>)</td><td>฿<?= number_format((float) $t['amount'], 2)?></td><td><?= e($t['status'])?></td><td><?php if($t['slip_path']): ?><a href="<?= e($t['slip_path'])?>" target="_blank" rel="noopener">ดู</a><?php endif; ?></td></tr><?php endforeach; ?>
</table>
<h2 class="h4 mt-4">คำสั่งซื้อ</h2>
<table class="table"><tr><th>เลขที่</th><th>สมาชิก</th><th>สินค้า</th><th>ยอด</th><th>สถานะ</th></tr>
<?php foreach($orders as $o): ?><tr><td><?= e($o['order_number'])?></td><td><?= e($o['name'])?></td><td><?= e($o['item_list'] ?? '')?></td><td>฿<?= number_format((float) $o['subtotal'], 2)?></td><td><?= e($o['status'])?></td></tr><?php endforeach; ?>
</table>
<?php foot() ?>
