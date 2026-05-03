</main>
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
