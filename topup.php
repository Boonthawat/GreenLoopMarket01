<?php
require 'init.php';
require 'controller/topup.php';

head('เติมเงิน Wallet', true, $balance);
?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-body h-100">
            <span class="badge bg-success align-self-start mb-2">THB WALLET</span>
            <h1 class="h3 text-success">เติมเงินเข้าวอลเล็ต</h1>
            <p class="text-muted">เลือกยอดที่ต้องการและแนบสลิป หลังส่งคำขอเงินจะเข้าบัญชีเมื่อผู้ดูแลอนุมัติ</p>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken)?>">
                <label for="amount" class="form-label">จำนวนเงิน (บาท)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">฿</span>
                    <input id="amount" type="number" name="amount" class="form-control" min="20" max="10000" step="10" list="topupAmounts" value="100" required>
                    <datalist id="topupAmounts">
                        <option value="100"><option value="300"><option value="500"><option value="1000">
                    </datalist>
                </div>
                <label for="slip" class="form-label">สลิปการชำระเงิน</label>
                <input id="slip" type="file" name="slip" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                <small class="form-text text-muted">รองรับ JPG, PNG, WEBP ขนาดไม่เกิน 5 MB</small>
                <button class="btn btn-success mt-3" type="submit" name="submit_topup">ส่งคำขอเติมเงิน</button>
            </form>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-body text-center h-100">
            <h2 class="h5 text-success">PromptPay QR (ตัวอย่าง)</h2>
            <p class="small text-muted">QR จำลองสำหรับทดสอบเท่านั้น ไม่สามารถใช้ชำระเงินจริงได้</p>
            <div class="bg-white border rounded p-3 mx-auto" style="width: 220px">
                <svg viewBox="0 0 210 210" role="img" aria-label="QR จำลอง" class="img-fluid">
                    <rect width="210" height="210" fill="#fff"/>
                    <path fill="#183c2b" d="M10 10h60v60H10zM20 20v40h40V20zM30 30h20v20H30zM140 10h60v60h-60zM150 20v40h40V20zM160 30h20v20h-20zM10 140h60v60H10zM20 150v40h40v-40zM30 160h20v20H30z"/>
                    <path fill="#183c2b" d="M85 10h15v15H85zM110 10h15v15h-15zM80 35h20v15H80zM110 35h15v25h-15zM80 65h15v15H80zM105 75h25v15h-25zM80 95h15v25H80zM105 100h15v15h-15zM130 90h15v25h-15zM155 85h15v20h-15zM180 85h20v15h-20zM100 130h15v20h-15zM125 125h15v15h-15zM150 115h15v25h-15zM175 115h25v15h-25zM80 145h15v15H80zM105 160h20v15h-20zM135 150h15v25h-15zM160 150h15v15h-15zM185 145h15v25h-15zM85 185h20v15H85zM120 185h15v15h-15zM150 180h15v20h-15zM175 180h25v20h-25z"/>
                    <rect x="83" y="130" width="48" height="16" rx="4" fill="#fff"/>
                    <text x="107" y="142" text-anchor="middle" font-size="10" font-family="Arial" font-weight="700" fill="#198754">DEMO</text>
                </svg>
            </div>
            <p class="small text-muted mt-3 mb-0">ระบบนี้จำลองการตรวจสอบสลิปโดยผู้ดูแล ไม่มีการตัดเงินหรือเชื่อมต่อธนาคาร</p>
        </div>
    </div>
</div>

<section class="mt-5">
    <h2 class="h4 text-success mb-3">ประวัติการเติมเงิน</h2>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>วันที่</th><th>จำนวน</th><th>สลิป</th><th>สถานะ</th></tr></thead>
                <tbody>
                    <?php foreach($history as $topup): ?>
                        <?php
                            $statusLabel = ['pending' => 'รอตรวจสอบ', 'approved' => 'อนุมัติแล้ว', 'rejected' => 'ปฏิเสธ'];
                            $statusClass = ['pending' => 'bg-warning text-dark', 'approved' => 'bg-success', 'rejected' => 'bg-danger'];
                        ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($topup['created_at']))?></td>
                            <td>฿<?= number_format((float) $topup['amount'], 2)?></td>
                            <td><a href="<?= e($topup['slip_path'])?>" target="_blank" rel="noopener">ดูสลิป</a></td>
                            <td><span class="badge <?= $statusClass[$topup['status']]?>"><?= $statusLabel[$topup['status']]?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$history): ?>
                        <tr><td colspan="4" class="text-center text-muted">ยังไม่มีประวัติเติมเงิน</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php foot() ?>
