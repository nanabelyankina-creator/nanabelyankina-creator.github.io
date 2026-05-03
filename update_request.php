<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/request.php');
    exit;
}

$cart = getCart();
foreach ($_POST['qty'] ?? [] as $id => $qty) {
    $productId = (int)$id;
    $value = (int)$qty;
    if ($value <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId] = min(999, $value);
    }
}
setCart($cart);

addFlash('success', 'Запрос КП обновлен.');
header('Location: ' . BASE_URL . '/request.php');
exit;
