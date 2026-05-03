<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/products.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$data = [
    'name' => trim($_POST['name'] ?? ''),
    'sku' => trim($_POST['sku'] ?? ''),
    'short_description' => trim($_POST['short_description'] ?? ''),
    'full_description' => trim($_POST['full_description'] ?? ''),
    'specifications' => trim($_POST['specifications'] ?? ''),
    'price' => (float)($_POST['price'] ?? 0),
    'is_visible' => isset($_POST['is_visible']) ? 1 : 0,
];

if ($data['name'] === '' || $data['sku'] === '') {
    header('Location: ' . BASE_URL . '/admin/product_form.php' . ($id ? '?id=' . $id : ''));
    exit;
}

$imagePath = null;

$cropped = trim((string)($_POST['cropped_image'] ?? ''));
if ($cropped !== '' && str_starts_with($cropped, 'data:image/')) {
    $parts = explode(',', $cropped, 2);
    $meta = $parts[0] ?? '';
    $payload = $parts[1] ?? '';
    if ($payload !== '' && str_contains($meta, ';base64')) {
        $raw = base64_decode($payload, true);
        if ($raw !== false && strlen($raw) > 0) {
            $filename = uniqid('product_', true) . '.png';
            $target = __DIR__ . '/../uploads/' . $filename;
            if (file_put_contents($target, $raw) !== false) {
                $imagePath = $filename;
            }
        }
    }
}

if ($imagePath === null && !empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (in_array($ext, $allowed, true)) {
        $filename = uniqid('product_', true) . '.' . $ext;
        $target = __DIR__ . '/../uploads/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = $filename;
        }
    }
}

$pdo = db();
if ($id > 0) {
    $sql = 'UPDATE products SET name=:name, sku=:sku, short_description=:short_description, full_description=:full_description, specifications=:specifications, price=:price, is_visible=:is_visible';
    $params = $data;
    if ($imagePath) {
        $sql .= ', image_path=:image_path';
        $params['image_path'] = $imagePath;
    }
    $sql .= ' WHERE id=:id';
    $params['id'] = $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} else {
    $stmt = $pdo->prepare('INSERT INTO products (name, sku, short_description, full_description, specifications, price, image_path, is_visible, created_at) VALUES (:name, :sku, :short_description, :full_description, :specifications, :price, :image_path, :is_visible, NOW())');
    $stmt->execute([
        'name' => $data['name'],
        'sku' => $data['sku'],
        'short_description' => $data['short_description'],
        'full_description' => $data['full_description'],
        'specifications' => $data['specifications'],
        'price' => $data['price'],
        'image_path' => $imagePath ?? '',
        'is_visible' => $data['is_visible'],
    ]);
}

header('Location: ' . BASE_URL . '/admin/products.php');
exit;
