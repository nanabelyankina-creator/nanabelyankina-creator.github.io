<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$contacts = getContacts();
$title = 'Контакты';
require __DIR__ . '/includes/header.php';
?>
<section class="page-head">
    <p class="eyebrow">Контакты</p>
    <h1>Свяжитесь с нами</h1>
    <p>Ответим на вопросы по тендерному сопровождению, поставкам и подготовке коммерческого предложения.</p>
</section>

<div class="contact-layout">
    <div class="card">
        <h3>Реквизиты для связи</h3>
        <p><strong>Адрес:</strong><br><?= e($contacts['address']) ?></p>
        <p><strong>Телефон:</strong><br><a href="tel:<?= e($contacts['phone']) ?>"><?= e($contacts['phone']) ?></a></p>
        <p><strong>E-mail:</strong><br><a href="mailto:<?= e($contacts['email']) ?>"><?= e($contacts['email']) ?></a></p>
    </div>

    <div class="card">
        <h3>Форма обратной связи</h3>
        <form method="post" action="<?= BASE_URL ?>/submit_feedback.php" class="form">
            <input required type="text" name="name" placeholder="Ваше имя">
            <input required type="text" name="contact" placeholder="Телефон или e-mail">
            <textarea required name="message" placeholder="Сообщение"></textarea>
            <button class="btn" type="submit">Отправить</button>
        </form>
    </div>
</div>

<section class="section-block">
    <h2>Мы на карте</h2>
    <div class="card map-embed">
        <iframe src="https://yandex.ru/map-widget/v1/?ll=37.628696%2C54.180985&z=18" loading="lazy" allowfullscreen></iframe>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
