</main>
<footer class="footer">
    <div class="container footer-grid">
        <div>
            <a class="logo footer-logo" href="<?= BASE_URL ?>/index.php">
                <span class="logo-mark">IT</span>
                <span><?= e(SITE_NAME) ?></span>
            </a>
            <p class="muted">Платформа и экспертное сопровождение закупок по 44-ФЗ, 223-ФЗ и 275-ФЗ для бизнеса и учреждений.</p>
        </div>
        <div class="footer-links">
            <a href="<?= BASE_URL ?>/about.php">О компании</a>
            <a href="<?= BASE_URL ?>/services.php">Услуги</a>
            <a href="<?= BASE_URL ?>/catalog.php">Каталог товаров</a>
            <a href="<?= BASE_URL ?>/contacts.php">Контакты</a>
            <a href="<?= BASE_URL ?>/about.php">Политика конфиденциальности</a>
            <a href="<?= BASE_URL ?>/request.php">Запрос КП</a>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>© <?= date('Y') ?>. Все права защищены.</p>
    </div>
</footer>
<?php $toasts = consumeFlash(); ?>
<?php if (!empty($toasts)): ?>
    <div class="toast-container" data-toasts>
        <?php foreach ($toasts as $toast): ?>
            <div class="toast <?= ($toast['type'] ?? '') === 'success' ? 'toast--success' : 'toast--error' ?>" role="status" aria-live="polite">
                <div class="toast-body">
                    <div class="toast-title"><?= ($toast['type'] ?? '') === 'success' ? 'Успешно' : 'Ошибка' ?></div>
                    <div class="toast-text"><?= e((string)($toast['message'] ?? '')) ?></div>
                </div>
                <button class="toast-close" type="button" aria-label="Закрыть">×</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
