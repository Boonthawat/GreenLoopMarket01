<?php
$user = me();
if (!$user || $user['status'] !== 'active') {
    go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
}
$userId = (int) $user['id'];
$pointsRow = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
$_SESSION['user']['points'] = (int) ($pointsRow['points'] ?? 0);

if (empty($_SESSION['market_csrf'])) {
    $_SESSION['market_csrf'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['market_csrf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_topup'])) {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($csrfToken, $submittedToken)) {
        go('topup.php', 'แบบฟอร์มหมดอายุ กรุณาลองใหม่', 'danger');
    }
    $amountInput = $_POST['amount'] ?? '';
    if (!is_string($amountInput) || !is_numeric($amountInput)) {
        go('topup.php', 'ระบุจำนวนเงินให้ถูกต้อง', 'danger');
    }

    $amountCents = (int) round((float) $amountInput * 100);
    if ($amountCents < 2000 || $amountCents > 1000000) {
        go('topup.php', 'เติมเงินได้ตั้งแต่ ฿20 ถึง ฿10,000', 'danger');
    }

    $file = $_FILES['slip'] ?? [];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 5 * 1024 * 1024) {
        go('topup.php', 'อัปโหลดรูปไม่สำเร็จ หรือไฟล์มีขนาดเกิน 5 MB', 'danger');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $extension = $extensions[$mime] ?? null;
    if (!$extension || !getimagesize($file['tmp_name'])) {
        go('topup.php', 'รองรับเฉพาะไฟล์ JPG, PNG หรือ WEBP', 'danger');
    }

    $directory = __DIR__ . '/../upload/slips';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        go('topup.php', 'สร้างโฟลเดอร์อัปโหลดไม่สำเร็จ', 'danger');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
        go('topup.php', 'บันทึกรูปภาพไม่สำเร็จ', 'danger');
    }
    $slipPath = 'upload/slips/' . $filename;

    $amount = $db->real_escape_string(number_format($amountCents / 100, 2, '.', ''));
    $slipPath = $db->real_escape_string($slipPath);
    $db->query(
        "INSERT INTO wallet_transactions
            (user_id, transaction_type, amount, slip_path, status)
         VALUES ($userId, 'topup', '$amount', '$slipPath', 'pending')"
    );
    go('topup.php', 'ส่งคำขอเติมเงินแล้ว รอผู้ดูแลตรวจสอบ');
}

$wallet = $db->query("SELECT wallet_balance FROM users WHERE id = $userId")->fetch_assoc();
$balance = $wallet['wallet_balance'] ?? '0.00';
$history = $db->query(
    "SELECT amount, slip_path, status, created_at
     FROM wallet_transactions
     WHERE user_id = $userId AND transaction_type = 'topup'
     ORDER BY created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
