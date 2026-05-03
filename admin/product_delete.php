<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

header('Location: ' . BASE_URL . '/admin/products.php');
exit;
