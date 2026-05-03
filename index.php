<?php
declare(strict_types=1);
$title = 'ООО ИТ-ТЕНДЕР - Главная';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero hero--single">
    <div class="hero-content">
        <p class="eyebrow">Закупки и тендерное сопровождение</p>
        <h1>Торги и закупки — собираем все, показываем лучшее</h1>
        <p>ООО «ИТ-ТЕНДЕР» помогает предприятиям находить перспективные закупки и проходить тендерные процедуры без лишних рисков. В одном окне: поиск, аналитика, уведомления и практическое сопровождение команды.</p>
        <div class="hero-actions">
            <a class="btn" href="<?= BASE_URL ?>/request.php">Начать бесплатно</a>
            <a class="btn btn-light" href="<?= BASE_URL ?>/services.php">Смотреть услуги</a>
        </div>
    </div>
</section>

<section class="section-block quick-pages">
    <h2>Основные разделы сайта</h2>
    <p class="section-intro">На главной странице размещено краткое описание возможностей, а детальная информация доступна на отдельных страницах сайта.</p>
    <div class="grid-3">
        <article class="card tool-card">
            <h3>О компании</h3>
            <p>Опыт, специализация и преимущества ООО «ИТ-ТЕНДЕР» в сфере закупок и тендерного сопровождения.</p>
            <a class="link-btn" href="<?= BASE_URL ?>/about.php">Перейти на страницу</a>
        </article>
        <article class="card tool-card">
            <h3>Услуги</h3>
            <p>Сопровождение закупок по 44-ФЗ, 223-ФЗ, 275-ФЗ, подготовка документации и консультации специалистов.</p>
            <a class="link-btn" href="<?= BASE_URL ?>/services.php">Смотреть услуги</a>
        </article>
        <article class="card tool-card">
            <h3>Каталог товаров</h3>
            <p>Каталог продукции с ценами, описаниями и возможностью добавить позиции в запрос на коммерческое предложение.</p>
            <a class="link-btn" href="<?= BASE_URL ?>/catalog.php">Открыть каталог</a>
        </article>
    </div>
</section>

<section class="section-block" id="features">
    <h2>В одном окне торги с 700+ электронных площадок</h2>
    <div class="benefits-row">
        <article class="card benefit-card">
            <h3>Быстрый поиск по условиям закупки</h3>
            <p>Ищите процедуры по отрасли, региону, бюджету и срокам в несколько кликов.</p>
        </article>
        <article class="card benefit-card">
            <h3>Каждая деталь контракта под рукой</h3>
            <p>Сразу видны требования к заявке, условия поставки и ключевые риски по документации.</p>
        </article>
        <article class="card benefit-card">
            <h3>Актуальные данные 24/7</h3>
            <p>Платформа непрерывно обновляет информацию, чтобы вы не пропускали важные изменения.</p>
        </article>
    </div>
</section>

<section class="section-block">
    <h2>Точный поиск тендеров с глубокой фильтрацией</h2>
    <p class="section-intro">Настраивайте фильтры по регионам, заказчикам, суммам и ключевым словам. Система находит даже тендеры в сканах документов и помогает быстро выделить закупки, подходящие именно вашему предприятию.</p>
    <div class="search-visual card">
        <div class="search-filters">
            <h3>Фильтры</h3>
            <p>Регион: РФ / СНГ</p>
            <p>Тип закупки: 44-ФЗ, 223-ФЗ, 275-ФЗ</p>
            <p>Сумма: от 500 000 до 20 000 000 ₽</p>
            <p>Отрасль: IT, оборудование, услуги</p>
        </div>
        <div class="search-results">
            <h3>Список тендеров</h3>
            <article class="result-item">
                <strong>Поставка серверного оборудования</strong>
                <span>9 800 000 ₽ · Москва · 223-ФЗ</span>
            </article>
            <article class="result-item">
                <strong>Сопровождение ИТ-инфраструктуры</strong>
                <span>4 200 000 ₽ · Санкт-Петербург · 44-ФЗ</span>
            </article>
            <article class="result-item">
                <strong>Поставка сетевого оборудования</strong>
                <span>7 450 000 ₽ · Казань · 223-ФЗ</span>
            </article>
        </div>
    </div>
</section>

<section class="section-block notify-block">
    <div class="phone-card card">
        <h3>Моментальные уведомления</h3>
        <p class="notify-badge">Новый тендер по вашим параметрам</p>
        <p class="notify-badge">Изменились сроки подачи заявки</p>
        <p class="notify-badge">Добавлены разъяснения заказчика</p>
    </div>
    <div>
        <h2>Моментальные уведомления с гибкой настройкой</h2>
        <ul class="check-list">
            <li>Уведомления о новых тендерах по вашим параметрам</li>
            <li>Изменения в документации и сроках</li>
            <li>Напоминания о важных датах</li>
        </ul>
        <a class="link-btn" href="<?= BASE_URL ?>/request.php">Настроить уведомления</a>
    </div>
</section>

<section class="section-block client-benefits" id="client-benefits">
    <h2>Что получает клиент</h2>
    <p class="section-intro">Наводите на блок — ниже появится краткое описание, как именно мы это делаем.</p>

    <div class="benefits-grid">
        <button class="card benefit-tile is-active" type="button"
                data-benefit-title="Подбор закупок по профилю компании"
                data-benefit-text="Собираем закупки по вашему ОКВЭД/нише, региону и требованиям. Дальше отсеиваем нерелевантные и оставляем те, где достижима целевая маржинальность с учетом логистики, сроков и условий контракта.">
            <h3>Подбор закупок по профилю</h3>
            <p class="muted">По отрасли, регионам и целевой маржинальности.</p>
        </button>

        <button class="card benefit-tile" type="button"
                data-benefit-title="Сопровождение заявок по 44‑ФЗ, 223‑ФЗ и 275‑ФЗ"
                data-benefit-text="Проверяем документацию, собираем пакет, контролируем сроки и разъяснения. Помогаем корректно оформить заявку и не потерять баллы на формальностях.">
            <h3>Сопровождение по 44‑ФЗ/223‑ФЗ/275‑ФЗ</h3>
            <p class="muted">Документы, сроки, уточнения, подача.</p>
        </button>

        <button class="card benefit-tile" type="button"
                data-benefit-title="Поддержка по контракту и исполнению"
                data-benefit-text="Сопровождаем этапы после победы: контроль сроков, закрывающие документы, коммуникация с заказчиком, учет рисков и соблюдение условий поставки/оказания услуг.">
            <h3>Контроль контракта и исполнения</h3>
            <p class="muted">Этапы, риски и взаимодействие с заказчиком.</p>
        </button>

        <button class="card benefit-tile" type="button"
                data-benefit-title="Запрос на КП по товарам из каталога"
                data-benefit-text="Формируете запрос на коммерческое предложение прямо на сайте: добавляете позиции из каталога, указываете количество и получаете единый запрос для расчета.">
            <h3>Запрос КП из каталога</h3>
            <p class="muted">Соберите позиции и отправьте один запрос.</p>
        </button>
    </div>

    <div class="benefit-details card" aria-live="polite">
        <h3 class="benefit-details-title">Подбор закупок по профилю компании</h3>
        <p class="benefit-details-text">Собираем закупки по вашему ОКВЭД/нише, региону и требованиям. Дальше отсеиваем нерелевантные и оставляем те, где достижима целевая маржинальность с учетом логистики, сроков и условий контракта.</p>
        <div class="benefit-details-actions">
            <a class="btn btn-light" href="<?= BASE_URL ?>/services.php">Посмотреть услуги</a>
            <a class="btn" href="<?= BASE_URL ?>/request.php">Начать бесплатно</a>
        </div>
    </div>
</section>

<section class="section-block" id="clients">
    <h2>Удобные инструменты для работы с тендерами</h2>
    <p class="section-intro">Сохраняйте интересующие закупки, отмечайте статусы, работайте с карточками и выгружайте отчеты в удобном формате для руководства и команды.</p>
    <div class="grid-3">
        <article class="card tool-card">
            <h3>Доступ к закупкам в 1 клик</h3>
            <p>Избранное, персональные подборки и быстрый переход к нужным карточкам тендеров.</p>
        </article>
        <article class="card tool-card">
            <h3>Выгрузка в Excel и PDF</h3>
            <p>Настраиваемые отчеты по выборке торгов для анализа и внутренней отчетности.</p>
        </article>
        <article class="card tool-card">
            <h3>Карточка тендера под контроль</h3>
            <p>Заметки, документы, история изменений и фиксация ключевых действий по каждой процедуре.</p>
        </article>
        <article class="card tool-card">
            <h3>Работа команды в едином контуре</h3>
            <p>Роли, статусы и прозрачный контроль этапов тендерной работы для всех участников процесса.</p>
        </article>
    </div>
</section>

<section class="stats" id="plans">
    <article class="stat-item card"><strong>2000+</strong><span>тендеров проанализировано специалистами ИТ-ТЕНДЕР</span></article>
    <article class="stat-item card"><strong>93%</strong><span>клиентов продолжают сотрудничество после первого периода</span></article>
    <article class="stat-item card"><strong>24/7</strong><span>мониторинг публикаций, правок и сроков процедур</span></article>
    <article class="stat-item card"><strong>12 лет</strong><span>практики в закупках и тендерном сопровождении предприятий</span></article>
</section>

<section class="section-block cta-extended" id="support">
    <div class="cta card">
        <div>
            <h2>Оставьте заявку, и мы вам перезвоним</h2>
            <p>Поможем настроить поиск тендеров под задачи вашего предприятия, подскажем стратегию участия и подготовим план сопровождения.</p>
            <form class="form-inline" method="post" action="<?= BASE_URL ?>/submit_feedback.php">
                <input type="text" name="name" placeholder="Имя" required>
                <input type="text" name="contact" placeholder="Телефон" required>
                <input type="text" name="company" placeholder="Компания">
                <button class="btn" type="submit">Отправить заявку</button>
            </form>
        </div>
        <div class="cta-illustration card">
            <h3>Персональный менеджер на связи</h3>
            <p>Деловой подход, понятные сроки и поддержка на всех этапах от подбора закупки до исполнения контракта.</p>
            <a class="btn btn-light" href="<?= BASE_URL ?>/contacts.php">Связаться с поддержкой</a>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
