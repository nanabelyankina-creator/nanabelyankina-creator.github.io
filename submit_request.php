<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/request.php');
    exit;
}

$cartItems = fetchCartProducts();
if (!$cartItems) {
    addFlash('error', 'Добавьте товары в запрос КП перед отправкой.');
    header('Location: ' . BASE_URL . '/request.php');
    exit;
}

$data = [
    'org_name' => trim($_POST['org_name'] ?? ''),
    'inn' => trim($_POST['inn'] ?? ''),
    'contact_person' => trim($_POST['contact_person'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'comment' => trim($_POST['comment'] ?? ''),
];

if ($data['org_name'] === '' || $data['contact_person'] === '' || $data['phone'] === '' || $data['email'] === '') {
    addFlash('error', 'Заполните обязательные поля для отправки запроса.');
    header('Location: ' . BASE_URL . '/request.php');
    exit;
}

$pdo = db();
$pdo->beginTransaction();

$stmt = $pdo->prepare('INSERT INTO quote_requests (org_name, inn, contact_person, phone, email, comment, status, created_at) VALUES (:org_name, :inn, :contact_person, :phone, :email, :comment, "Новая", NOW())');
$stmt->execute($data);
$requestId = (int)$pdo->lastInsertId();

$itemStmt = $pdo->prepare('INSERT INTO quote_request_items (request_id, product_id, product_name, price, qty) VALUES (:request_id, :product_id, :product_name, :price, :qty)');
foreach ($cartItems as $item) {
    $itemStmt->execute([
        'request_id' => $requestId,
        'product_id' => (int)$item['id'],
        'product_name' => $item['name'],
        'price' => $item['price'],
        'qty' => $item['qty'],
    ]);
}

$pdo->commit();
setCart([]);

addFlash('success', 'Запрос успешно отправлен. Менеджер свяжется с вами.');
header('Location: ' . BASE_URL . '/request.php');
exit;
