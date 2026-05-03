<?php
declare(strict_types=1);
$title = 'О компании';
require_once __DIR__ . '/includes/header.php';
$content = getPageContent('about');
?>
<section class="page-head">
    <p class="eyebrow">О компании</p>
    <h1>Надежный партнер в сфере закупок</h1>
    <p>Мы работаем с государственными и коммерческими заказчиками, помогая выстраивать прозрачный и предсказуемый процесс закупок.</p>
</section>

<div class="page-text"><?= nl2br(e($content)) ?></div>

<section class="section-block">
    <div class="grid-3">
        <article class="card">
            <h3>Экспертиза</h3>
            <p>Практический опыт сопровождения процедур по федеральному законодательству.</p>
        </article>
        <article class="card">
            <h3>Ответственность</h3>
            <p>Контроль сроков, документов и этапов исполнения договорных обязательств.</p>
        </article>
        <article class="card">
            <h3>Поддержка</h3>
            <p>Консультации на каждом этапе: от заявки до финального согласования коммерческого предложения.</p>
        </article>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
