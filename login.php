<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

ensureUsersStorage();

if (isLoggedIn()) {
    if (isStaffUser()) {
        header('Location: ' . BASE_URL . '/admin/index.php');
        exit;
    }
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
    } else {
        $stmt = db()->prepare('SELECT * FROM users WHERE login = :login LIMIT 1');
        $stmt->execute(['login' => $phone]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = 'Пользователь с таким телефоном не найден';
        } elseif (!isset($user['password_hash']) || !is_string($user['password_hash']) || $user['password_hash'] === '') {
            $error = 'У учетной записи не задан пароль (обратитесь к администратору)';
        } elseif (password_verify($password, (string)$user['password_hash'])) {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_login'] = (string)$user['login'];
            $role = isset($user['role']) && is_string($user['role']) && $user['role'] !== '' ? $user['role'] : 'client';
            $_SESSION['user_role'] = $role;

            if (in_array($_SESSION['user_role'], ['admin', 'worker'], true)) {
                header('Location: ' . BASE_URL . '/admin/index.php');
                exit;
            }

            header('Location: ' . BASE_URL . '/index.php');
            exit;
        } else {
            $error = 'Неверный пароль';
        }
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<main class="container auth-page">
    <section class="section-block card auth-card">
        <h1>Вход в систему</h1>
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
            <button class="btn" type="submit">Войти</button>
        </form>
        <div class="auth-bottom">
            <p class="muted auth-note">Вы еще не зарегистрированы?</p>
            <a class="btn btn-light auth-register-btn" href="<?= BASE_URL ?>/register.php">Зарегистрируйтесь</a>
        </div>
    </section>
</main>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
