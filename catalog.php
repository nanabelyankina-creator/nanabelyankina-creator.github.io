<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$q = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM products WHERE is_visible = 1';
$params = [];
if ($q !== '') {
    $sql .= ' AND (name LIKE :q OR sku LIKE :q)';
    $params['q'] = '%' . $q . '%';
}
$sql .= ' ORDER BY id DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$title = 'Каталог товаров';
require __DIR__ . '/includes/header.php';
?>
<section class="page-head">
    <p class="eyebrow">Каталог</p>
    <h1>Каталог товаров</h1>
    <p>Подберите нужные позиции, добавьте их в запрос и отправьте данные для подготовки коммерческого предложения.</p>
</section>

<form method="get" class="search-form search-inline">
    <input type="text" name="q" placeholder="Поиск по названию или артикулу" value="<?= e($q) ?>">
    <button class="btn" type="submit">Найти</button>
</form>

<?php if (!$products): ?>
    <div class="card">
        <p>По вашему запросу ничего не найдено. Попробуйте изменить формулировку поиска.</p>
    </div>
<?php else: ?>
    <p class="muted">Найдено позиций: <?= count($products) ?></p>
    <div class="grid-3 catalog-grid">
        <?php foreach ($products as $item): ?>
            <article class="card product-card">
                <?php if (!empty($item['image_path'])): ?>
                    <div class="product-thumb">
                        <img src="<?= BASE_URL . '/uploads/' . e($item['image_path']) ?>" alt="<?= e($item['name']) ?>" loading="lazy">
                    </div>
                <?php else: ?>
                    <div class="product-thumb product-thumb--empty">
                        <span class="muted">Без фото</span>
                    </div>
                <?php endif; ?>
                <p class="product-sku"><?= e($item['sku']) ?></p>
                <h3><?= e($item['name']) ?></h3>
                <p><?= e($item['short_description']) ?></p>
                <p class="price"><?= number_format((float)$item['price'], 2, '.', ' ') ?> ₽</p>
                <a class="btn" href="<?= BASE_URL ?>/product.php?id=<?= (int)$item['id'] ?>">Подробнее</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
