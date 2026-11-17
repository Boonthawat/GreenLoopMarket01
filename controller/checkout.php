<?php
require_once __DIR__ . '/../lib/marketplace.php';
require_once __DIR__ . '/../lib/checkout.php';

$user = market_require_user();
$userId = (int) $user['id'];
marketplace_handle_checkout($db, $userId);

$checkout = marketplace_checkout_data($db, $userId);
$balance = $checkout['balance'];
$availablePoints = $checkout['availablePoints'];
$order = $checkout['order'];
$orderItems = $checkout['orderItems'];
$cart = $checkout['cart'];
$cartProducts = $checkout['cartProducts'];
$subtotalCents = $checkout['subtotalCents'];
$cartReady = $checkout['cartReady'];
$maxPoints = $checkout['maxPoints'];
