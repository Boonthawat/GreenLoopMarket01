<?php
function market_require_user(): array
{
    $user = me();
    if (!$user || $user['status'] !== 'active') {
        go('login.php', 'กรุณาเข้าสู่ระบบด้วยบัญชีที่ใช้งานได้', 'danger');
    }
    return $user;
}

function market_require_admin(): array
{
    $user = market_require_user();
    if ($user['role'] !== 'admin') {
        go('index.php', 'หน้านี้สำหรับผู้ดูแลระบบเท่านั้น', 'danger');
    }
    return $user;
}

function market_csrf_token(): string
{
    if (empty($_SESSION['market_csrf'])) {
        $_SESSION['market_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['market_csrf'];
}

function market_check_csrf(string $redirect): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(market_csrf_token(), $token)) {
        go($redirect, 'แบบฟอร์มหมดอายุ กรุณาลองใหม่', 'danger');
    }
}

function market_ensure_wallet(mysqli $db, int $userId): void
{
    $db->query("INSERT IGNORE INTO wallets (user_id, balance) VALUES ($userId, 0.00)");
}

function market_wallet_balance(mysqli $db, int $userId): string
{
    market_ensure_wallet($db, $userId);
    $row = $db->query("SELECT balance FROM wallets WHERE user_id = $userId")->fetch_assoc();
    return $row['balance'] ?? '0.00';
}

function market_refresh_points(mysqli $db, int $userId): int
{
    $row = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
    $points = (int) ($row['points'] ?? 0);
    $_SESSION['user']['points'] = $points;
    return $points;
}

function market_money(int $cents): string
{
    return number_format($cents / 100, 2, '.', '');
}

function market_upload_image(string $field, string $folder, bool $required = false): ?string
{
    if (!in_array($folder, ['products', 'slips'], true)) {
        throw new RuntimeException('โฟลเดอร์อัปโหลดไม่ถูกต้อง');
    }

    $file = $_FILES[$field] ?? null;
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            throw new RuntimeException('กรุณาเลือกรูปภาพ');
        }
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('อัปโหลดรูปไม่สำเร็จ หรือไฟล์มีขนาดเกิน 5 MB');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($extensions[$mime]) || !getimagesize($file['tmp_name'])) {
        throw new RuntimeException('รองรับเฉพาะไฟล์ JPG, PNG หรือ WEBP');
    }

    $directory = __DIR__ . '/../upload/' . $folder;
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('สร้างโฟลเดอร์อัปโหลดไม่สำเร็จ');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
        throw new RuntimeException('บันทึกรูปภาพไม่สำเร็จ');
    }

    return 'upload/' . $folder . '/' . $filename;
}
