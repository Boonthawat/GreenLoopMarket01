<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../lib/marketplace.php';

$admin = market_require_admin();
$adminId = (int) $admin['id'];
$tab = $_GET['tab'] ?? 'overview';
if (!in_array($tab, ['overview', 'products', 'topups', 'orders'], true)) $tab = 'overview';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    market_check_csrf('admin.php?tab=' . urlencode($tab));

    if (isset($_POST['save_product'])) {
        $id = filter_var($_POST['id'] ?? '0', FILTER_VALIDATE_INT);
        $name = is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '';
        $description = is_string($_POST['description'] ?? null) ? trim($_POST['description']) : '';
        $ecoImpact = is_string($_POST['eco_impact'] ?? null) ? trim($_POST['eco_impact']) : '';
        $price = $_POST['price_thb'] ?? '';
        $stock = filter_var($_POST['stock'] ?? '', FILTER_VALIDATE_INT);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id === false || $id < 0 || $name === '' || $ecoImpact === '' || !is_string($price) || !is_numeric($price) || (float) $price <= 0 || (float) $price > 99999999.99 || $stock === false || $stock < 0) {
            go('admin.php?tab=products', 'กรุณาตรวจสอบข้อมูลสินค้า', 'danger');
        }

        try {
            $imagePath = market_upload_image('image', 'products');
        } catch (RuntimeException $exception) {
            go('admin.php?tab=products', $exception->getMessage(), 'danger');
        }

        if ($id > 0) {
            try {
                $db->begin_transaction();
                $nameSql = $db->real_escape_string($name);
                $priceSql = $db->real_escape_string($price);
                $db->query("UPDATE products SET name = '$nameSql', price_thb = '$priceSql', stock = $stock, is_active = $active WHERE id = $id");

                if ($imagePath !== null) {
                    $descriptionSql = $db->real_escape_string($description);
                    $imagePathSql = $db->real_escape_string($imagePath);
                    $ecoImpactSql = $db->real_escape_string($ecoImpact);
                    $db->query(
                        "UPDATE product_details SET description = '$descriptionSql', image_path = '$imagePathSql',
                         eco_impact = '$ecoImpactSql' WHERE product_id = $id"
                    );
                } else {
                    $descriptionSql = $db->real_escape_string($description);
                    $ecoImpactSql = $db->real_escape_string($ecoImpact);
                    $db->query(
                        "UPDATE product_details SET description = '$descriptionSql', eco_impact = '$ecoImpactSql'
                         WHERE product_id = $id"
                    );
                }
                $db->commit();
            } catch (mysqli_sql_exception $exception) {
                $db->rollback();
                error_log('Product update failed: ' . $exception->getMessage());
                go('admin.php?tab=products', 'บันทึกสินค้าไม่สำเร็จ กรุณาลองใหม่', 'danger');
            }
            go('admin.php?tab=products', 'บันทึกการแก้ไขสินค้าแล้ว');
        }

        $imagePath = $imagePath ?? '';
        try {
            $db->begin_transaction();
            $nameSql = $db->real_escape_string($name);
            $priceSql = $db->real_escape_string($price);
            $db->query("INSERT INTO products (name, price_thb, stock, is_active) VALUES ('$nameSql', '$priceSql', $stock, $active)");
            $productId = (int) $db->insert_id;

            $descriptionSql = $db->real_escape_string($description);
            $imagePathSql = $db->real_escape_string($imagePath);
            $ecoImpactSql = $db->real_escape_string($ecoImpact);
            $db->query(
                "INSERT INTO product_details (product_id, description, image_path, eco_impact)
                 VALUES ($productId, '$descriptionSql', '$imagePathSql', '$ecoImpactSql')"
            );
            $db->commit();
        } catch (mysqli_sql_exception $exception) {
            $db->rollback();
            error_log('Product insert failed: ' . $exception->getMessage());
            go('admin.php?tab=products', 'เพิ่มสินค้าไม่สำเร็จ กรุณาลองใหม่', 'danger');
        }
        go('admin.php?tab=products', 'เพิ่มสินค้าแล้ว');
    }

    if (isset($_POST['delete_product'])) {
        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) go('admin.php?tab=products', 'เลือกสินค้าไม่ถูกต้อง', 'danger');
        $db->query("DELETE FROM products WHERE id = $id");
        go('admin.php?tab=products', 'ลบสินค้าแล้ว');
    }

    if (isset($_POST['approve_topup']) || isset($_POST['reject_topup'])) {
        $id = filter_var($_POST['topup_id'] ?? '', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) go('admin.php?tab=topups', 'เลือกรายการเติมเงินไม่ถูกต้อง', 'danger');

        $transactionStarted = false;
        try {
            $db->begin_transaction();
            $transactionStarted = true;
            $topup = $db->query("SELECT * FROM topups WHERE id = $id FOR UPDATE")->fetch_assoc();
            if (!$topup || $topup['status'] !== 'pending') {
                throw new DomainException('รายการนี้ถูกตรวจสอบแล้วหรือไม่มีอยู่');
            }

            if (isset($_POST['approve_topup'])) {
                $userId = (int) $topup['user_id'];
                $amount = number_format((float) $topup['amount'], 2, '.', '');
                market_ensure_wallet($db, $userId);
                $db->query("UPDATE wallets SET balance = balance + '$amount' WHERE user_id = $userId");

                $description = $db->real_escape_string('เติมเงินผ่านการตรวจสอบ');
                $db->query(
                    "INSERT INTO wallet_transactions (user_id, transaction_type, amount, reference_id, description)
                     VALUES ($userId, 'topup', '$amount', $id, '$description')"
                );
                $newStatus = 'approved';
                $message = 'อนุมัติและเติมเงินเข้าบัญชีแล้ว';
            } else {
                $newStatus = 'rejected';
                $message = 'ปฏิเสธคำขอเติมเงินแล้ว';
            }

            $newStatusSql = $db->real_escape_string($newStatus);
            $db->query("UPDATE topups SET status = '$newStatusSql' WHERE id = $id");
            $db->query("INSERT INTO topup_reviews (topup_id, reviewed_by) VALUES ($id, $adminId)");
            $db->commit();
            $transactionStarted = false;
            go('admin.php?tab=topups', $message);
        } catch (DomainException $exception) {
            if ($transactionStarted) $db->rollback();
            go('admin.php?tab=topups', $exception->getMessage(), 'danger');
        } catch (mysqli_sql_exception $exception) {
            if ($transactionStarted) $db->rollback();
            error_log('Top-up review failed: ' . $exception->getMessage());
            go('admin.php?tab=topups', 'ตรวจสอบคำขอไม่สำเร็จ กรุณาลองใหม่', 'danger');
        }
    }

    if (isset($_POST['update_order'])) {
        $id = filter_var($_POST['order_id'] ?? '', FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        if (!$id || $id < 1 || !in_array($status, ['processing', 'shipped', 'delivered'], true)) {
            go('admin.php?tab=orders', 'ข้อมูลสถานะคำสั่งซื้อไม่ถูกต้อง', 'danger');
        }
        $statusSql = $db->real_escape_string($status);
        $db->query("UPDATE orders SET status = '$statusSql' WHERE id = $id");
        go('admin.php?tab=orders', 'อัปเดตสถานะจัดส่งแล้ว');
    }
}

$productForm = [
    'id' => 0,
    'name' => '',
    'description' => '',
    'image_path' => '',
    'price_thb' => '',
    'stock' => 0,
    'eco_impact' => '',
    'is_active' => 1,
];
if (isset($_GET['edit'])) {
    $editId = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if (!$editId || $editId < 1) go('admin.php?tab=products', 'ไม่พบสินค้า', 'danger');
    $productForm = $db->query(
        "SELECT p.*, d.description, d.image_path, d.eco_impact
         FROM products p JOIN product_details d ON d.product_id = p.id WHERE p.id = $editId"
    )->fetch_assoc();
    if (!$productForm) go('admin.php?tab=products', 'ไม่พบสินค้า', 'danger');
}

$metrics = [];
$metrics['sales'] = $db->query('SELECT COALESCE(SUM(wallet_paid), 0) FROM orders')->fetch_row()[0];
$metrics['points'] = $db->query('SELECT COALESCE(SUM(points_used), 0) FROM order_details')->fetch_row()[0];
$metrics['pending_topups'] = $db->query("SELECT COUNT(*) FROM topups WHERE status = 'pending'")->fetch_row()[0];
$metrics['low_stock'] = $db->query('SELECT COUNT(*) FROM products WHERE is_active = 1 AND stock <= 5')->fetch_row()[0];

$products = $db->query(
    'SELECT p.*, d.description, d.image_path, d.eco_impact
     FROM products p JOIN product_details d ON d.product_id = p.id
     ORDER BY p.created_at DESC'
)->fetch_all(MYSQLI_ASSOC);
$pendingTopups = $db->query("SELECT t.*, u.name, u.email FROM topups t JOIN users u ON u.id = t.user_id WHERE t.status = 'pending' ORDER BY t.created_at ASC")->fetch_all(MYSQLI_ASSOC);
$orders = $db->query(
    "SELECT o.*, d.points_used, d.discount_amount, d.shipping_address, u.name, u.email,
        (SELECT GROUP_CONCAT(CONCAT(product_name, ' × ', quantity) SEPARATOR ', ')
         FROM order_items WHERE order_id = o.id) AS item_list
     FROM orders o
     JOIN order_details d ON d.order_id = o.id
     JOIN users u ON u.id = o.user_id
     ORDER BY o.created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
