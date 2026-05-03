<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/catalog.php');
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
$qty = max(1, min(999, (int)($_POST['qty'] ?? 1)));

$stmt = db()->prepare('SELECT id FROM products WHERE id = :id AND is_visible = 1');
$stmt->execute(['id' => $productId]);
if (!$stmt->fetch()) {
    addFlash('error', 'Товар не найден или скрыт.');
    header('Location: ' . BASE_URL . '/catalog.php');
    exit;
}

$cart = getCart();
$cart[$productId] = ($cart[$productId] ?? 0) + $qty;
setCart($cart);

addFlash('success', 'Товар успешно добавлен в запрос КП.');
header('Location: ' . BASE_URL . '/request.php');
exit;
