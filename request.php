<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$items = fetchCartProducts();
$total = 0;
foreach ($items as $it) {
    $total += (float)$it['sum'];
}

$title = 'Запрос на КП';
require __DIR__ . '/includes/header.php';
?>
<section class="page-head">
    <p class="eyebrow">Запрос на КП</p>
    <h1>Сформируйте заявку на коммерческое предложение</h1>
    <p>Проверьте выбранные позиции, укажите контактные данные, и менеджер подготовит КП по вашему запросу.</p>
</section>

<?php if ($items): ?>
<form method="post" action="<?= BASE_URL ?>/update_request.php">
    <table class="table">
        <thead><tr><th>Товар</th><th>Цена</th><th>Количество</th><th>Сумма</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['name']) ?></td>
                <td><?= number_format((float)$item['price'], 2, '.', ' ') ?> ₽</td>
                <td><input type="number" min="0" max="999" name="qty[<?= (int)$item['id'] ?>]" value="<?= (int)$item['qty'] ?>"></td>
                <td><?= number_format((float)$item['sum'], 2, '.', ' ') ?> ₽</td>
                <td><a href="<?= BASE_URL ?>/remove_request_item.php?id=<?= (int)$item['id'] ?>">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Итого:</strong> <?= number_format($total, 2, '.', ' ') ?> ₽</p>
    <button class="btn" type="submit">Обновить список</button>
</form>

<h2>Контактные данные</h2>
<form method="post" action="<?= BASE_URL ?>/submit_request.php" class="form">
    <input required type="text" name="org_name" placeholder="Название организации">
    <input type="text" name="inn" placeholder="ИНН (необязательно)">
    <input required type="text" name="contact_person" placeholder="Контактное лицо (ФИО)">
    <input required type="text" name="phone" placeholder="Телефон">
    <input required type="email" name="email" placeholder="E-mail">
    <textarea name="comment" placeholder="Комментарий"></textarea>
    <button class="btn" type="submit">Отправить запрос на КП</button>
</form>
<?php else: ?>
    <div class="card">
        <p>Вы пока не добавили товары в запрос. Перейдите в <a href="<?= BASE_URL ?>/catalog.php">каталог</a>, выберите позиции и возвращайтесь к оформлению.</p>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
