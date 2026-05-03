<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';

$productsCount = (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$requestsCount = (int)db()->query('SELECT COUNT(*) FROM quote_requests')->fetchColumn();
$newRequests = (int)db()->query("SELECT COUNT(*) FROM quote_requests WHERE status = 'Новая'")->fetchColumn();
?>
<h1>Панель управления</h1>
<div class="grid-3">
    <article class="card"><h3>Товаров</h3><p><?= $productsCount ?></p></article>
    <article class="card"><h3>Всего заявок</h3><p><?= $requestsCount ?></p></article>
    <article class="card"><h3>Новые заявки</h3><p><?= $newRequests ?></p></article>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
