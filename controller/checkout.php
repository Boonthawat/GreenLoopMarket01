<?php
$user = market_require_user();
$userId = (int) $user['id'];
$cart = $_SESSION['market_cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    market_check_csrf('checkout.php');

    if (isset($_POST['remove_id'])) {
        $removeId = filter_var($_POST['remove_id'], FILTER_VALIDATE_INT);
        if ($removeId) {
            unset($cart[$removeId]);
            $_SESSION['market_cart'] = $cart;
        }
        go('checkout.php', 'อัปเดตรายการในตะกร้าแล้ว');
    }

    if (isset($_POST['update_cart'])) {
        $quantities = $_POST['quantity'] ?? [];
        if (!is_array($quantities)) go('checkout.php', 'ข้อมูลจำนวนสินค้าไม่ถูกต้อง', 'danger');
        foreach ($quantities as $id => $quantity) {
            $productId = filter_var($id, FILTER_VALIDATE_INT);
            $count = filter_var($quantity, FILTER_VALIDATE_INT);
            if ($productId && $productId > 0) {
                if ($count === false || $count < 1) {
                    unset($cart[$productId]);
                } else {
                    $cart[$productId] = min($count, 99);
                }
            }
        }
        $_SESSION['market_cart'] = $cart;
        go('checkout.php', 'อัปเดตตะกร้าแล้ว');
    }

    if (isset($_POST['place_order'])) {
        $requestedPoints = filter_var($_POST['points_used'] ?? '0', FILTER_VALIDATE_INT);
        $address = is_string($_POST['shipping_address'] ?? null) ? trim($_POST['shipping_address']) : '';
        $addressLength = preg_match_all('/./us', $address);
        if ($requestedPoints === false || $requestedPoints < 0) {
            go('checkout.php', 'จำนวน GreenPoints ไม่ถูกต้อง', 'danger');
        }
        if ($addressLength === false || $addressLength < 5 || $addressLength > 500) {
            go('checkout.php', 'กรุณากรอกที่อยู่จัดส่ง 5 ถึง 500 ตัวอักษร', 'danger');
        }
        if (!$cart) go('index.php', 'ตะกร้าสินค้าว่างเปล่า', 'danger');

        $transactionStarted = false;
        try {
            $db->begin_transaction();
            $transactionStarted = true;
            $lockedProducts = [];
            foreach ($cart as $id => $quantity) {
                $productId = (int) $id;
                $product = $db->query(
                    "SELECT p.id, p.name, p.price_thb, p.stock, d.eco_impact
                     FROM products p JOIN product_details d ON d.product_id = p.id
                     WHERE p.id = $productId AND p.is_active = 1 FOR UPDATE"
                )->fetch_assoc();
                if (!$product) throw new DomainException('มีสินค้าบางรายการถูกปิดขายแล้ว กรุณาตรวจสอบตะกร้า');
                $lockedProducts[$productId] = $product;
            }

            $subtotalCents = 0;
            foreach ($cart as $id => $quantity) {
                $product = $lockedProducts[(int) $id];
                $quantity = (int) $quantity;
                if ($quantity < 1 || $quantity > (int) $product['stock']) {
                    throw new DomainException('สินค้า ' . $product['name'] . ' มีจำนวนไม่พอ');
                }
                $unitCents = (int) round((float) $product['price_thb'] * 100);
                $subtotalCents += $unitCents * $quantity;
            }

            $pointsRow = $db->query("SELECT points FROM users WHERE id = $userId FOR UPDATE")->fetch_assoc();
            $currentPoints = (int) ($pointsRow['points'] ?? 0);
            $maxPoints = max(0, min($currentPoints, intdiv(intdiv($subtotalCents, 2), 10)));
            if ($requestedPoints > $maxPoints) {
                throw new DomainException('ใช้ GreenPoints ได้สูงสุด ' . number_format($maxPoints) . ' คะแนน');
            }

            market_ensure_wallet($db, $userId);
            $walletRow = $db->query("SELECT balance FROM wallets WHERE user_id = $userId FOR UPDATE")->fetch_assoc();
            $walletCents = (int) round((float) ($walletRow['balance'] ?? 0) * 100);
            $discountCents = $requestedPoints * 10;
            $walletDueCents = $subtotalCents - $discountCents;
            if ($walletCents < $walletDueCents) {
                throw new DomainException('ยอดเงินใน Wallet ไม่พอ กรุณาเติมเงินก่อนชำระ');
            }

            $orderNumber = 'GL' . date('ymd') . strtoupper(bin2hex(random_bytes(3)));
            $orderNumberSql = $db->real_escape_string($orderNumber);
            $subtotal = market_money($subtotalCents);
            $discount = market_money($discountCents);
            $walletDue = market_money($walletDueCents);
            $db->query(
                "INSERT INTO orders (order_number, user_id, subtotal, wallet_paid)
                 VALUES ('$orderNumberSql', $userId, '$subtotal', '$walletDue')"
            );
            $orderId = (int) $db->insert_id;
            $addressSql = $db->real_escape_string($address);
            $db->query(
                "INSERT INTO order_details (order_id, points_used, discount_amount, shipping_address)
                 VALUES ($orderId, $requestedPoints, '$discount', '$addressSql')"
            );

            foreach ($cart as $id => $quantity) {
                $productId = (int) $id;
                $product = $lockedProducts[$productId];
                $quantity = (int) $quantity;
                $unitPrice = market_money((int) round((float) $product['price_thb'] * 100));
                $productName = $db->real_escape_string($product['name']);
                $ecoImpact = $db->real_escape_string($product['eco_impact']);
                $db->query(
                    "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, eco_impact)
                     VALUES ($orderId, $productId, '$productName', $quantity, '$unitPrice', '$ecoImpact')"
                );
                $db->query("UPDATE products SET stock = stock - $quantity WHERE id = $productId AND stock >= $quantity");
                if ($db->affected_rows !== 1) {
                    throw new DomainException('สต็อกสินค้าเปลี่ยนแปลง กรุณาลองสั่งซื้อใหม่');
                }
            }

            if ($requestedPoints > 0) {
                $db->query("UPDATE users SET points = points - $requestedPoints WHERE id = $userId AND points >= $requestedPoints");
                if ($db->affected_rows !== 1) {
                    throw new DomainException('GreenPoints เปลี่ยนแปลง กรุณาลองสั่งซื้อใหม่');
                }
            }

            $db->query(
                "UPDATE wallets SET balance = balance - '$walletDue'
                 WHERE user_id = $userId AND balance >= '$walletDue'"
            );
            if ($walletDueCents > 0 && $db->affected_rows !== 1) {
                throw new DomainException('ยอด Wallet เปลี่ยนแปลง กรุณาลองสั่งซื้อใหม่');
            }

            if ($walletDueCents > 0) {
                $negativeAmount = '-' . $walletDue;
                $description = $db->real_escape_string('ชำระคำสั่งซื้อ ' . $orderNumber);
                $db->query(
                    "INSERT INTO wallet_transactions (user_id, transaction_type, amount, reference_id, description)
                     VALUES ($userId, 'purchase', '$negativeAmount', $orderId, '$description')"
                );
            }

            $db->commit();
            $transactionStarted = false;
            $_SESSION['market_cart'] = [];
            go('checkout.php?order=' . urlencode($orderNumber), 'สั่งซื้อสำเร็จ');
        } catch (DomainException $exception) {
            if ($transactionStarted) $db->rollback();
            go('checkout.php', $exception->getMessage(), 'danger');
        } catch (mysqli_sql_exception $exception) {
            if ($transactionStarted) $db->rollback();
            error_log('Checkout failed: ' . $exception->getMessage());
            go('checkout.php', 'ชำระเงินไม่สำเร็จ กรุณาลองใหม่', 'danger');
        }
    }
}

$balance = market_wallet_balance($db, $userId);
$pointsRow = $db->query("SELECT points FROM users WHERE id = $userId")->fetch_assoc();
$availablePoints = (int) ($pointsRow['points'] ?? 0);
$_SESSION['user']['points'] = $availablePoints;

$order = null;
$orderItems = [];
if (isset($_GET['order']) && is_string($_GET['order'])) {
    $orderNumberSql = $db->real_escape_string($_GET['order']);
    $order = $db->query(
        "SELECT o.*, d.points_used, d.discount_amount
         FROM orders o JOIN order_details d ON d.order_id = o.id
         WHERE o.order_number = '$orderNumberSql' AND o.user_id = $userId"
    )->fetch_assoc();
    if ($order) {
        $orderId = (int) $order['id'];
        $orderItems = $db->query("SELECT * FROM order_items WHERE order_id = $orderId")->fetch_all(MYSQLI_ASSOC);
    }
}

$cart = $_SESSION['market_cart'] ?? [];
$products = [];
foreach ($cart as $id => $quantity) {
    $productId = (int) $id;
    $product = $db->query(
        "SELECT p.*, d.description, d.image_path, d.eco_impact
         FROM products p JOIN product_details d ON d.product_id = p.id
         WHERE p.id = $productId"
    )->fetch_assoc();
    if ($product) $products[$productId] = $product;
}

$subtotalCents = 0;
$cartReady = (bool) $cart;
foreach ($cart as $id => $quantity) {
    $productId = (int) $id;
    if (!isset($products[$productId]) || !$products[$productId]['is_active'] || (int) $quantity > (int) $products[$productId]['stock']) {
        $cartReady = false;
        continue;
    }
    $subtotalCents += (int) round((float) $products[$productId]['price_thb'] * 100) * (int) $quantity;
}
$maxPoints = max(0, min($availablePoints, intdiv(intdiv($subtotalCents, 2), 10)));
