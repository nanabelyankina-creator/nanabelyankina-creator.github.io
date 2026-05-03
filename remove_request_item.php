<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$cart = getCart();
unset($cart[$id]);
setCart($cart);

addFlash('success', 'Товар удален из запроса КП.');
header('Location: ' . BASE_URL . '/request.php');
exit;
