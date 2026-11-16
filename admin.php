<?php
require 'init.php';
require 'controller/marketplace_admin.php';
head('จัดการร้านค้า');
?>
<h1 class="h2 text-success mb-4">GreenLoop Marketplace · Admin</h1>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><div class="card card-body h-100"><small class="text-muted">ยอดชำระจาก Wallet</small><strong class="fs-4 text-success">฿<?= number_format((float) $metrics['sales'], 2)?></strong></div></div>
    <div class="col-6 col-xl-3"><div class="card card-body h-100"><small class="text-muted">GreenPoints ที่ใช้</small><strong class="fs-4 text-success"><?= number_format((int) $metrics['points'])?></strong></div></div>
    <div class="col-6 col-xl-3"><div class="card card-body h-100"><small class="text-muted">คำขอเติมเงินรอตรวจ</small><strong class="fs-4 text-success"><?= number_format((int) $metrics['pending_topups'])?></strong></div></div>
    <div class="col-6 col-xl-3"><div class="card card-body h-100"><small class="text-muted">สินค้าใกล้หมด (≤ 5)</small><strong class="fs-4 text-success"><?= number_format((int) $metrics['low_stock'])?></strong></div></div>
</div>

<ul class="nav nav-tabs mb-4" role="tablist">
    <?php foreach(['overview' => 'ภาพรวม', 'products' => 'สินค้า', 'topups' => 'เติมเงิน', 'orders' => 'คำสั่งซื้อ'] as $key => $label): ?>
        <li class="nav-item">
            <a class="nav-link <?= $tab === $key ? 'active' : ''?>" href="admin.php?tab=<?= $key?>"><?= $label?></a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if($tab === 'overview'): ?>
    <div class="card card-body">
        <h2 class="h5 text-success">ยินดีต้อนรับผู้ดูแลร้านค้า</h2>
        <p class="mb-0">จัดการสินค้า ตรวจสอบสลิปเติมเงิน และติดตามสถานะการจัดส่งได้จากแท็บด้านบน</p>
    </div>
<?php elseif($tab === 'products'): ?>
    <div class="card card-body mb-4">
        <h2 class="h4 text-success"><?= $productForm['id'] ? 'แก้ไขสินค้า' : 'เพิ่มสินค้าใหม่'?></h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
            <input type="hidden" name="id" value="<?= (int) $productForm['id']?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="product-name">ชื่อสินค้า</label><input id="product-name" name="name" class="form-control" maxlength="150" value="<?= e($productForm['name'])?>" required></div>
                <div class="col-md-3"><label class="form-label" for="product-price">ราคา (บาท)</label><input id="product-price" name="price_thb" type="number" min="0.01" step="0.01" class="form-control" value="<?= e($productForm['price_thb'])?>" required></div>
                <div class="col-md-3"><label class="form-label" for="product-stock">สต็อก</label><input id="product-stock" name="stock" type="number" min="0" step="1" class="form-control" value="<?= (int) $productForm['stock']?>" required></div>
                <div class="col-md-6"><label class="form-label" for="product-impact">ข้อมูลผลกระทบต่อสิ่งแวดล้อม</label><input id="product-impact" name="eco_impact" class="form-control" maxlength="255" value="<?= e($productForm['eco_impact'])?>" required></div>
                <div class="col-md-6"><label class="form-label" for="product-image">รูปสินค้า (JPG, PNG, WEBP)</label><input id="product-image" name="image" type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp"></div>
                <div class="col-12"><label class="form-label" for="product-description">รายละเอียด</label><textarea id="product-description" name="description" rows="2" class="form-control"><?= e($productForm['description'])?></textarea></div>
                <div class="col-12 form-check ms-2"><input id="product-active" name="is_active" type="checkbox" class="form-check-input" value="1" <?= $productForm['is_active'] ? 'checked' : ''?>><label for="product-active" class="form-check-label">เปิดขายสินค้า</label></div>
                <div class="col-12">
                    <button class="btn btn-success" name="save_product" type="submit">บันทึกสินค้า</button>
                    <?php if($productForm['id']): ?><a href="admin.php?tab=products" class="btn btn-outline-secondary">ยกเลิกแก้ไข</a><?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>สินค้า</th><th>ราคา</th><th>สต็อก</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                        <tr>
                            <td><?= e($product['name'])?></td>
                            <td>฿<?= number_format((float) $product['price_thb'], 2)?></td>
                            <td><?= (int) $product['stock']?></td>
                            <td><?= $product['is_active'] ? 'เปิดขาย' : 'ปิดขาย'?></td>
                            <td class="text-nowrap">
                                <a href="admin.php?tab=products&amp;edit=<?= (int) $product['id']?>" class="btn btn-sm btn-outline-success">แก้ไข</a>
                                <form method="post" class="d-inline" onsubmit="return confirm('ยืนยันลบสินค้านี้?')">
                                    <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                                    <input type="hidden" name="id" value="<?= (int) $product['id']?>">
                                    <button class="btn btn-sm btn-outline-danger" name="delete_product" type="submit">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$products): ?><tr><td colspan="5" class="text-center text-muted">ยังไม่มีสินค้า</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php elseif($tab === 'topups'): ?>
    <div class="card card-body">
        <h2 class="h4 text-success">คำขอเติมเงินที่รอตรวจสอบ</h2>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>สมาชิก</th><th>ยอดเงิน</th><th>สลิป</th><th>วันที่</th><th>ตรวจสอบ</th></tr></thead>
                <tbody>
                    <?php foreach($pendingTopups as $topup): ?>
                        <tr>
                            <td><?= e($topup['name'])?><br><small class="text-muted"><?= e($topup['email'])?></small></td>
                            <td>฿<?= number_format((float) $topup['amount'], 2)?></td>
                            <td><a href="<?= e($topup['slip_path'])?>" target="_blank" rel="noopener"><img src="<?= e($topup['slip_path'])?>" alt="สลิปเติมเงิน" class="img-thumbnail" width="72" height="72"></a></td>
                            <td><?= date('d/m/Y H:i', strtotime($topup['created_at']))?></td>
                            <td class="text-nowrap">
                                <?php foreach(['approve_topup' => 'อนุมัติ', 'reject_topup' => 'ปฏิเสธ'] as $action => $label): ?>
                                    <form method="post" class="d-inline" onsubmit="return confirm('<?= $label?>คำขอนี้?')">
                                        <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                                        <input type="hidden" name="topup_id" value="<?= (int) $topup['id']?>">
                                        <button class="btn btn-sm <?= $action === 'approve_topup' ? 'btn-success' : 'btn-outline-danger'?>" name="<?= $action?>" type="submit"><?= $label?></button>
                                    </form>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$pendingTopups): ?><tr><td colspan="5" class="text-center text-muted">ไม่มีคำขอค้างตรวจสอบ</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="card card-body">
        <h2 class="h4 text-success">คำสั่งซื้อและการจัดส่ง</h2>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>คำสั่งซื้อ / สินค้า</th><th>สมาชิก / ที่อยู่จัดส่ง</th><th>ยอดสินค้า</th><th>แต้ม/ส่วนลด</th><th>Wallet</th><th>สถานะจัดส่ง</th></tr></thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td><?= e($order['order_number'])?><br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($order['created_at']))?></small><br><small><?= e($order['item_list'] ?? '')?></small></td>
                            <td><?= e($order['name'])?><br><small class="text-muted"><?= nl2br(e($order['shipping_address']))?></small></td>
                            <td>฿<?= number_format((float) $order['subtotal'], 2)?></td>
                            <td><?= number_format((int) $order['points_used'])?> / ฿<?= number_format((float) $order['discount_amount'], 2)?></td>
                            <td>฿<?= number_format((float) $order['wallet_paid'], 2)?></td>
                            <td>
                                <form method="post" class="d-flex gap-2">
                                    <input type="hidden" name="csrf_token" value="<?= e(market_csrf_token())?>">
                                    <input type="hidden" name="order_id" value="<?= (int) $order['id']?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <?php foreach(['processing' => 'กำลังเตรียม', 'shipped' => 'จัดส่งแล้ว', 'delivered' => 'ส่งถึงแล้ว'] as $value => $label): ?>
                                            <option value="<?= $value?>" <?= $order['status'] === $value ? 'selected' : ''?>><?= $label?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-sm btn-success text-nowrap" type="submit" name="update_order">บันทึก</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$orders): ?><tr><td colspan="6" class="text-center text-muted">ยังไม่มีคำสั่งซื้อ</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<?php foot() ?>
