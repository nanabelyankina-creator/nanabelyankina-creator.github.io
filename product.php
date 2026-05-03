<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM products WHERE id = :id AND is_visible = 1 LIMIT 1');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit('Товар не найден');
}

$title = $product['name'];
require __DIR__ . '/includes/header.php';
?>
<h1><?= e($product['name']) ?></h1>
<div class="product">
    <div>
        <?php if (!empty($product['image_path'])): ?>
            <img src="<?= BASE_URL . '/uploads/' . e($product['image_path']) ?>" alt="<?= e($product['name']) ?>" class="product-image">
        <?php else: ?>
            <div class="no-image">Изображение отсутствует</div>
        <?php endif; ?>
    </div>
    <div>
        <p><strong>Артикул:</strong> <?= e($product['sku']) ?></p>
        <p><strong>Цена:</strong> <?= number_format((float)$product['price'], 2, '.', ' ') ?> ₽</p>
        <p><?= nl2br(e($product['full_description'])) ?></p>
        <h3>Характеристики</h3>
        <pre class="specs"><?= e($product['specifications']) ?></pre>

        <form method="post" action="<?= BASE_URL ?>/add_to_request.php" class="inline-form">
            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
            <label>Количество
                <input type="number" name="qty" value="1" min="1" max="999">
            </label>
            <button class="btn" type="submit">Добавить в запрос на КП</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
