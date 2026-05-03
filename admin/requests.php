<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';

$requests = db()->query('SELECT * FROM quote_requests ORDER BY id DESC')->fetchAll();
?>
<h1>Заявки на КП</h1>
<table class="table">
    <thead><tr><th>ID</th><th>Организация</th><th>Контакт</th><th>Статус</th><th>Дата</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($requests as $r): ?>
        <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= e($r['org_name']) ?></td>
            <td><?= e($r['contact_person']) ?></td>
            <td><?= e($r['status']) ?></td>
            <td><?= e($r['created_at']) ?></td>
            <td><a href="<?= BASE_URL ?>/admin/request_view.php?id=<?= (int)$r['id'] ?>">Открыть</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/footer.php'; ?>
