<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function addFlash(string $type, string $message): void
{
    $type = $type === 'success' ? 'success' : 'error';
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

function consumeFlash(): array
{
    $items = [];
    if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
        $items = $_SESSION['flash'];
    }
    unset($_SESSION['flash']);
    return $items;
}

function tableExists(string $table): bool
{
    try {
        db()->query("SELECT 1 FROM `$table` LIMIT 1");
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

/**
 * Backward-compatible storage: migrate admins -> users if needed.
 * This prevents "can't login" when DB was imported with old schema.
 */
function ensureUsersStorage(): void
{
    if (!empty($_SESSION['users_storage_checked'])) {
        return;
    }
    $_SESSION['users_storage_checked'] = 1;

    if (tableExists('users')) {
        return;
    }
    if (!tableExists('admins')) {
        return;
    }

    $pdo = db();
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS users ('
        . 'id INT AUTO_INCREMENT PRIMARY KEY,'
        . 'login VARCHAR(100) NOT NULL UNIQUE,'
        . 'role VARCHAR(20) NOT NULL DEFAULT "client",'
        . 'password_hash VARCHAR(255) NOT NULL,'
        . 'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        . ')'
    );

    $pdo->exec('INSERT IGNORE INTO users (id, login, role, password_hash, created_at) SELECT id, login, role, password_hash, created_at FROM admins');
}

function getPageContent(string $slug): string
{
    $stmt = db()->prepare('SELECT content FROM site_pages WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();
    return $row['content'] ?? '';
}

function getContacts(): array
{
    $row = db()->query('SELECT * FROM site_contacts LIMIT 1')->fetch();
    return $row ?: ['address' => '', 'phone' => '', 'email' => ''];
}

function getCart(): array
{
    if (!isset($_SESSION['quote_cart']) || !is_array($_SESSION['quote_cart'])) {
        $_SESSION['quote_cart'] = [];
    }
    return $_SESSION['quote_cart'];
}

function setCart(array $cart): void
{
    $_SESSION['quote_cart'] = $cart;
}

function cartCount(): int
{
    return array_sum(getCart());
}

function fetchCartProducts(): array
{
    $cart = getCart();
    if (!$cart) {
        return [];
    }

    $ids = array_map('intval', array_keys($cart));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("SELECT * FROM products WHERE is_visible = 1 AND id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    foreach ($products as &$product) {
        $product['qty'] = (int)($cart[$product['id']] ?? 1);
        $product['sum'] = $product['qty'] * (float)$product['price'];
    }

    return $products;
}

function requireAdmin(): void
{
    ensureUsersStorage();
    $role = $_SESSION['user_role'] ?? '';
    if (empty($_SESSION['user_id']) || !in_array($role, ['admin', 'worker'], true)) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function currentUserRole(): string
{
    return (string)($_SESSION['user_role'] ?? '');
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function isStaffUser(): bool
{
    return in_array(currentUserRole(), ['admin', 'worker'], true);
}

function normalizePhone(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    if ($digits === '') {
        return '';
    }
    if (strlen($digits) === 11 && $digits[0] === '8') {
        $digits = '7' . substr($digits, 1);
    }
    if (strlen($digits) === 10) {
        $digits = '7' . $digits;
    }
    if (strlen($digits) !== 11 || $digits[0] !== '7') {
        return '';
    }
    return $digits;
}
