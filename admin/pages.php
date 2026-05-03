<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about = trim($_POST['about'] ?? '');
    $services = trim($_POST['services'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $stmt = db()->prepare('UPDATE site_pages SET content = :content WHERE slug = :slug');
    $stmt->execute(['content' => $about, 'slug' => 'about']);
    $stmt->execute(['content' => $services, 'slug' => 'services']);

    $contactStmt = db()->prepare('UPDATE site_contacts SET address=:address, phone=:phone, email=:email WHERE id=1');
    $contactStmt->execute(['address' => $address, 'phone' => $phone, 'email' => $email]);
}

$about = getPageContent('about');
$services = getPageContent('services');
$contacts = getContacts();
?>
<h1>Управление текстами и контактами</h1>
<form method="post" class="form">
    <h3>О компании</h3>
    <textarea name="about" rows="6"><?= e($about) ?></textarea>
    <h3>Услуги</h3>
    <textarea name="services" rows="8"><?= e($services) ?></textarea>
    <h3>Контакты</h3>
    <input type="text" name="address" value="<?= e($contacts['address']) ?>" placeholder="Адрес">
    <input type="text" name="phone" value="<?= e($contacts['phone']) ?>" placeholder="Телефон">
    <input type="email" name="email" value="<?= e($contacts['email']) ?>" placeholder="E-mail">
    <button class="btn" type="submit">Сохранить изменения</button>
</form>
<?php require_once __DIR__ . '/footer.php'; ?>
