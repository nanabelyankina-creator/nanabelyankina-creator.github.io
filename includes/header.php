<?php
require_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? SITE_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container header-inner">
        <a class="logo" href="<?= BASE_URL ?>/index.php">
            <span class="logo-mark">IT</span>
            <span><?= e(SITE_NAME) ?></span>
        </a>
        <button class="menu-toggle" type="button" aria-label="Открыть меню">Меню</button>
        <nav class="menu" id="main-menu">
            <a href="<?= BASE_URL ?>/about.php">О компании</a>
            <a href="<?= BASE_URL ?>/services.php">Услуги</a>
            <a href="<?= BASE_URL ?>/catalog.php">Каталог товаров</a>
            <a href="<?= BASE_URL ?>/contacts.php">Контакты</a>
            <?php if (!isLoggedIn()): ?>
                <a class="btn btn-outline btn-sm nav-auth-btn" href="<?= BASE_URL ?>/login.php">Вход</a>
            <?php else: ?>
                <?php if (isStaffUser()): ?><a class="btn btn-outline btn-sm nav-auth-btn" href="<?= BASE_URL ?>/admin/index.php">Кабинет</a><?php endif; ?>
                <a class="btn btn-outline btn-sm nav-auth-btn" href="<?= BASE_URL ?>/logout.php">Выйти</a>
            <?php endif; ?>
            <a class="cart-link" href="<?= BASE_URL ?>/request.php">Запрос КП (<?= cartCount() ?>)</a>
        </nav>
    </div>
</header>
<main class="container">
