<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = trim($_POST['status'] ?? 'Новая');
    $stmt = db()->prepare('UPDATE quote_requests SET status = :status WHERE id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);
}

$stmt = db()->prepare('SELECT * FROM quote_requests WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$request = $stmt->fetch();
if (!$request) {
    exit('Заявка не найдена');
}

$itemStmt = db()->prepare('SELECT * FROM quote_request_items WHERE request_id = :id');
$itemStmt->execute(['id' => $id]);
$items = $itemStmt->fetchAll();
?>
<h1>Заявка #<?= (int)$request['id'] ?></h1>
<p><strong>Организация:</strong> <?= e($request['org_name']) ?></p>
<p><strong>ИНН:</strong> <?= e($request['inn']) ?></p>
<p><strong>Контактное лицо:</strong> <?= e($request['contact_person']) ?></p>
<p><strong>Телефон:</strong> <?= e($request['phone']) ?></p>
<p><strong>E-mail:</strong> <?= e($request['email']) ?></p>
<p><strong>Комментарий:</strong> <?= e($request['comment']) ?></p>
<p><strong>Дата:</strong> <?= e($request['created_at']) ?></p>

<h2>Товары</h2>
<table class="table">
    <thead><tr><th>Название</th><th>Цена</th><th>Кол-во</th></tr></thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['product_name']) ?></td>
            <td><?= number_format((float)$item['price'], 2, '.', ' ') ?> ₽</td>
            <td><?= (int)$item['qty'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<form method="post" class="inline-form">
    <label>Статус
        <select name="status">
            <?php foreach (['Новая', 'В обработке', 'Завершена'] as $status): ?>
                <option value="<?= e($status) ?>" <?= $request['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button class="btn" type="submit">Сохранить статус</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>
