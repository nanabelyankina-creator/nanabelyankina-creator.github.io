<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';

$q = trim((string)($_GET['q'] ?? ''));
$products = [];
if ($q !== '') {
    $stmt = db()->prepare('SELECT * FROM products WHERE name LIKE :q OR sku LIKE :q ORDER BY id DESC');
    $stmt->execute(['q' => '%' . $q . '%']);
    $products = $stmt->fetchAll();
} else {
    $products = db()->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
}
?>
<h1>Управление каталогом</h1>
<div class="inline-form" style="justify-content: space-between;">
    <form method="get" class="search-form search-inline" action="<?= BASE_URL ?>/admin/products.php">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Поиск по названию или артикулу (SKU)">
        <button class="btn btn-light" type="submit">Найти</button>
    </form>
    <a class="btn" href="<?= BASE_URL ?>/admin/product_form.php">Добавить товар</a>
</div>
<table class="table">
    <thead><tr><th>ID</th><th>Название</th><th>Артикул</th><th>Цена</th><th>Статус</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= (int)$p['id'] ?></td>
            <td><?= e($p['name']) ?></td>
            <td><?= e($p['sku']) ?></td>
            <td><?= number_format((float)$p['price'], 2, '.', ' ') ?></td>
            <td><?= (int)$p['is_visible'] === 1 ? 'Показывать' : 'Скрыт' ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/product_form.php?id=<?= (int)$p['id'] ?>">Редактировать</a> |
                <a href="<?= BASE_URL ?>/admin/product_delete.php?id=<?= (int)$p['id'] ?>" onclick="return confirm('Удалить товар?')">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php if ($q !== '' && !$products): ?>
    <div class="card">
        <p>По запросу «<?= e($q) ?>» ничего не найдено.</p>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
