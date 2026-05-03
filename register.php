<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

ensureUsersStorage();

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneInput = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = normalizePhone($phoneInput);

    if ($phone === '') {
        $error = 'Введите корректный номер телефона';
    } elseif (mb_strlen($password) < 6) {
        $error = 'Пароль должен быть не короче 6 символов';
    } else {
        $checkStmt = db()->prepare('SELECT id FROM users WHERE login = :login LIMIT 1');
        $checkStmt->execute(['login' => $phone]);
        if ($checkStmt->fetch()) {
            $error = 'Пользователь с таким телефоном уже зарегистрирован';
        } else {
            $insertStmt = db()->prepare('INSERT INTO users (login, role, password_hash) VALUES (:login, :role, :password_hash)');
            $insertStmt->execute([
                'login' => $phone,
                'role' => 'client',
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            $_SESSION['user_id'] = (int)db()->lastInsertId();
            $_SESSION['user_login'] = $phone;
            $_SESSION['user_role'] = 'client';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<main class="container auth-page">
    <section class="section-block card auth-card">
        <h1>Регистрация</h1>
        <p class="muted">Введите телефон и пароль. Телефон будет использоваться как логин для входа.</p>
        <?php if ($error): ?>
            <div class="toast-container" data-toasts>
                <div class="toast toast--error" role="status" aria-live="polite">
                    <div class="toast-body">
                        <div class="toast-title">Ошибка</div>
                        <div class="toast-text"><?= e($error) ?></div>
                    </div>
                    <button class="toast-close" type="button" aria-label="Закрыть">×</button>
                </div>
            </div>
        <?php endif; ?>
        <form method="post" class="form auth-form">
            <input type="text" name="phone" placeholder="+7 (___)-___-__ __" data-phone-mask required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button class="btn" type="submit">Зарегистрироваться</button>
        </form>
        <a class="btn btn-light" href="<?= BASE_URL ?>/login.php">Уже есть аккаунт? Войти</a>
    </section>
</main>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
