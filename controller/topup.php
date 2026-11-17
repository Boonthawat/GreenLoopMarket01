<?php
$user = market_require_user();
$userId = (int) $user['id'];
market_refresh_points($db, $userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_topup'])) {
    market_check_csrf('topup.php');
    $amountInput = $_POST['amount'] ?? '';
    if (!is_string($amountInput) || !is_numeric($amountInput)) {
        go('topup.php', 'ระบุจำนวนเงินให้ถูกต้อง', 'danger');
    }

    $amountCents = (int) round((float) $amountInput * 100);
    if ($amountCents < 2000 || $amountCents > 1000000) {
        go('topup.php', 'เติมเงินได้ตั้งแต่ ฿20 ถึง ฿10,000', 'danger');
    }

    try {
        $slipPath = market_upload_image('slip', 'slips', true);
    } catch (RuntimeException $exception) {
        go('topup.php', $exception->getMessage(), 'danger');
    }

    $amount = $db->real_escape_string(market_money($amountCents));
    $slipPath = $db->real_escape_string((string) $slipPath);
    $db->query(
        "INSERT INTO wallet_transactions
            (user_id, transaction_type, amount, slip_path, status)
         VALUES ($userId, 'topup', '$amount', '$slipPath', 'pending')"
    );
    go('topup.php', 'ส่งคำขอเติมเงินแล้ว รอผู้ดูแลตรวจสอบ');
}

$balance = market_wallet_balance($db, $userId);
$history = $db->query(
    "SELECT amount, slip_path, status, created_at
     FROM wallet_transactions
     WHERE user_id = $userId AND transaction_type = 'topup'
     ORDER BY created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
