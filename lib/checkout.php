<?php
function marketplace_handle_checkout(mysqli $db, int $userId): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    market_check_csrf('shop.php#cartModal');
    $cart = $_SESSION['market_cart'] ?? [];

    if (isset($_POST['remove_id'])) {
        $id = filter_var($_POST['remove_id'], FILTER_VALIDATE_INT);
        if ($id) unset($cart[$id]);
        $_SESSION['market_cart'] = $cart;
        go('shop.php#cartModal', 'อัปเดตรายการในตะกร้าแล้ว');
    }
    if (isset($_POST['update_cart'])) {
        $quantities = $_POST['quantity'] ?? [];
        if (!is_array($quantities)) go('shop.php#cartModal', 'ข้อมูลจำนวนสินค้าไม่ถูกต้อง', 'danger');
        foreach ($quantities as $id => $quantity) {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            $count = filter_var($quantity, FILTER_VALIDATE_INT);
            if ($id && $id > 0) {
                if ($count === false || $count < 1) unset($cart[$id]);
                else $cart[$id] = min($count, 99);
            }
        }
        $_SESSION['market_cart'] = $cart;
        go('shop.php#cartModal', 'อัปเดตตะกร้าแล้ว');
    }
    if (!isset($_POST['place_order'])) return;

    $points = filter_var($_POST['points_used'] ?? '0', FILTER_VALIDATE_INT);
    $address = is_string($_POST['shipping_address'] ?? null) ? trim($_POST['shipping_address']) : '';
    $length = preg_match_all('/./us', $address);
    if ($points === false || $points < 0) go('shop.php#cartModal', 'จำนวน GreenPoints ไม่ถูกต้อง', 'danger');
    if ($length === false || $length < 5 || $length > 500) go('shop.php#cartModal', 'กรุณากรอกที่อยู่จัดส่ง 5 ถึง 500 ตัวอักษร', 'danger');
    marketplace_place_order($db, $userId, $cart, $points, $address);
}

function marketplace_place_order(mysqli $db, int $userId, array $cart, int $points, string $address): void
{
    if (!$cart) go('index.php', 'ตะกร้าสินค้าว่างเปล่า', 'danger');
    $started = false;
    try {
        $db->begin_transaction();
        $started = true;
        $products = [];
        foreach ($cart as $id => $quantity) {
            $id = (int) $id;
            $product = $db->query("SELECT id, name, price_thb, stock, eco_impact FROM products WHERE id = $id AND is_active = 1 FOR UPDATE")->fetch_assoc();
            if (!$product) throw new DomainException('มีสินค้าบางรายการถูกปิดขายแล้ว กรุณาตรวจสอบตะกร้า');
            $products[$id] = $product;
        }
        $subtotal = 0;
        foreach ($cart as $id => $quantity) {
            $product = $products[(int) $id];
            $quantity = (int) $quantity;
            if ($quantity < 1 || $quantity > (int) $product['stock']) throw new DomainException('สินค้า ' . $product['name'] . ' มีจำนวนไม่พอ');
            $subtotal += (int) round((float) $product['price_thb'] * 100) * $quantity;
        }
        $user = $db->query("SELECT points, wallet_balance FROM users WHERE id = $userId FOR UPDATE")->fetch_assoc();
        $maxPoints = max(0, min((int) ($user['points'] ?? 0), intdiv(intdiv($subtotal, 2), 10)));
        if ($points > $maxPoints) throw new DomainException('ใช้ GreenPoints ได้สูงสุด ' . number_format($maxPoints) . ' คะแนน');
        $discount = $points * 10;
        $due = $subtotal - $discount;
        if ((int) round((float) ($user['wallet_balance'] ?? 0) * 100) < $due) throw new DomainException('ยอดเงินใน Wallet ไม่พอ กรุณาเติมเงินก่อนชำระ');

        $number = 'GL' . date('ymd') . strtoupper(bin2hex(random_bytes(3)));
        $numberSql = $db->real_escape_string($number);
        $addressSql = $db->real_escape_string($address);
        $db->query("INSERT INTO orders (order_number, user_id, subtotal, wallet_paid, points_used, discount_amount, shipping_address)
            VALUES ('$numberSql', $userId, '" . market_money($subtotal) . "', '" . market_money($due) . "', $points, '" . market_money($discount) . "', '$addressSql')");
        $orderId = (int) $db->insert_id;
        foreach ($cart as $id => $quantity) {
            $id = (int) $id;
            $quantity = (int) $quantity;
            $product = $products[$id];
            $name = $db->real_escape_string($product['name']);
            $impact = $db->real_escape_string($product['eco_impact']);
            $price = market_money((int) round((float) $product['price_thb'] * 100));
            $db->query("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, eco_impact) VALUES ($orderId, $id, '$name', $quantity, '$price', '$impact')");
            $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $id AND stock >= $quantity");
            if ($db->affected_rows !== 1) throw new DomainException('สต็อกสินค้าเปลี่ยนแปลง กรุณาลองสั่งซื้อใหม่');
        }
        if ($points > 0) {
            $db->query("UPDATE users SET points = points - $points WHERE id = $userId AND points >= $points");
            if ($db->affected_rows !== 1) throw new DomainException('GreenPoints เปลี่ยนแปลง กรุณาลองสั่งซื้อใหม่');
        }
        $dueMoney = market_money($due);
        $db->query("UPDATE users SET wallet_balance = wallet_balance - '$dueMoney' WHERE id = $userId AND wallet_balance >= '$dueMoney'");
        if ($due > 0 && $db->affected_rows !== 1) throw new DomainException('ยอด Wallet เปลี่ยนแปลง กรุณาลองสั่งซื้อใหม่');
        if ($due > 0) $db->query("INSERT INTO wallet_transactions (user_id, transaction_type, amount) VALUES ($userId, 'purchase', '-$dueMoney')");

        $db->commit();
        $started = false;
        $_SESSION['market_cart'] = [];
        go('shop.php?order=' . urlencode($number) . '#cartModal', 'สั่งซื้อสำเร็จ');
    } catch (DomainException $exception) {
        if ($started) $db->rollback();
        go('shop.php#cartModal', $exception->getMessage(), 'danger');
    } catch (mysqli_sql_exception $exception) {
        if ($started) $db->rollback();
        error_log('Checkout failed: ' . $exception->getMessage());
        go('shop.php#cartModal', 'ชำระเงินไม่สำเร็จ กรุณาลองใหม่', 'danger');
    }
}

function marketplace_checkout_data(mysqli $db, int $userId): array
{
    $balance = market_wallet_balance($db, $userId);
    $points = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
    $availablePoints = (int) ($points['points'] ?? 0);
    $_SESSION['user']['points'] = $availablePoints;
    $order = null;
    $items = [];
    if (isset($_GET['order']) && is_string($_GET['order'])) {
        $number = $db->real_escape_string($_GET['order']);
        $order = $db->query("SELECT * FROM orders WHERE order_number = '$number' AND user_id = $userId")->fetch_assoc();
        if ($order) $items = $db->query('SELECT * FROM order_items WHERE order_id = ' . (int) $order['id'])->fetch_all(MYSQLI_ASSOC);
    }
    $cart = $_SESSION['market_cart'] ?? [];
    $products = [];
    foreach ($cart as $id => $quantity) {
        $id = (int) $id;
        $product = $db->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        if ($product) $products[$id] = $product;
    }
    $subtotal = 0;
    $ready = (bool) $cart;
    foreach ($cart as $id => $quantity) {
        $id = (int) $id;
        if (!isset($products[$id]) || !$products[$id]['is_active'] || (int) $quantity > (int) $products[$id]['stock']) {
            $ready = false;
            continue;
        }
        $subtotal += (int) round((float) $products[$id]['price_thb'] * 100) * (int) $quantity;
    }
    return [
        'balance' => $balance, 'availablePoints' => $availablePoints, 'order' => $order,
        'orderItems' => $items, 'cart' => $cart, 'cartProducts' => $products,
        'subtotalCents' => $subtotal, 'cartReady' => $ready,
        'maxPoints' => max(0, min($availablePoints, intdiv(intdiv($subtotal, 2), 10))),
    ];
}
