<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/contacts.php');
    exit;
}

$data = [
    'name' => trim($_POST['name'] ?? ''),
    'contact' => trim($_POST['contact'] ?? ''),
    'message' => trim($_POST['message'] ?? ''),
];

if ($data['name'] === '' || $data['contact'] === '' || $data['message'] === '') {
    addFlash('error', 'Заполните все поля перед отправкой сообщения.');
    header('Location: ' . BASE_URL . '/contacts.php');
    exit;
}

$stmt = db()->prepare('INSERT INTO feedback_messages (name, contact, message, created_at) VALUES (:name, :contact, :message, NOW())');
$stmt->execute($data);

addFlash('success', 'Сообщение отправлено. Мы свяжемся с вами.');
header('Location: ' . BASE_URL . '/contacts.php');
exit;
