<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container header-inner">
        <a class="logo" href="<?= BASE_URL ?>/index.php" title="На главную">
            <span class="logo-mark">IT</span>
            <span><?= e(SITE_NAME) ?></span>
        </a>
        <nav class="menu">
            <span class="muted">Роль: <?= e(currentUserRole() === 'worker' ? 'Сотрудник' : 'Администратор') ?></span>
            <a href="<?= BASE_URL ?>/admin/products.php">Каталог</a>
            <a href="<?= BASE_URL ?>/admin/requests.php">Заявки</a>
            <a href="<?= BASE_URL ?>/admin/pages.php">Страницы</a>
            <a href="<?= BASE_URL ?>/admin/logout.php">Выход</a>
        </nav>
    </div>
</header>
<main class="container">
