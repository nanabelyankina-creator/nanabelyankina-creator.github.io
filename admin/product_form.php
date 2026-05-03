<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';

$id = (int)($_GET['id'] ?? 0);
$editing = $id > 0;

$product = [
    'name' => '',
    'sku' => '',
    'short_description' => '',
    'full_description' => '',
    'specifications' => '',
    'price' => '0',
    'is_visible' => 1,
    'image_path' => '',
];

if ($editing) {
    $stmt = db()->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if ($row) {
        $product = $row;
    }
}
?>
<h1><?= $editing ? 'Редактирование товара' : 'Добавление товара' ?></h1>
<div class="admin-product-layout">
    <form id="product-form" method="post" action="<?= BASE_URL ?>/admin/product_save.php" enctype="multipart/form-data" class="form" data-crop-form>
        <input type="hidden" name="id" value="<?= $editing ? (int)$id : 0 ?>">
        <input type="hidden" name="cropped_image" value="" data-cropped-image>

        <div class="field">
            <div class="field-label">Название товара</div>
            <input required type="text" name="name" value="<?= e($product['name']) ?>" placeholder="Например: Ноутбук офисный LT-101">
        </div>

        <div class="field">
            <div class="field-label">Артикул / код (SKU)</div>
            <input required type="text" name="sku" value="<?= e($product['sku']) ?>" placeholder="Например: LT-101">
        </div>

        <div class="field">
            <div class="field-label">Краткое описание</div>
            <textarea name="short_description" placeholder="1–2 предложения"><?= e($product['short_description']) ?></textarea>
        </div>

        <div class="field">
            <div class="field-label">Полное описание</div>
            <textarea name="full_description" placeholder="Подробное описание товара"><?= e($product['full_description']) ?></textarea>
        </div>

        <div class="field">
            <div class="field-label">Характеристики</div>
            <textarea name="specifications" placeholder="Можно списком, каждую характеристику с новой строки"><?= e($product['specifications']) ?></textarea>
        </div>

        <div class="field">
            <div class="field-label">Цена (₽)</div>
            <input required type="number" step="0.01" min="0" name="price" value="<?= e((string)$product['price']) ?>" placeholder="Например: 68990.00">
        </div>

        <div class="field">
            <label class="check-row" style="align-items: center;">
                <span class="field-label" style="min-width: 200px;">Показывать в каталоге</span>
                <input class="check" type="checkbox" name="is_visible" value="1" <?= (int)$product['is_visible'] === 1 ? 'checked' : '' ?>>
                <span class="muted">(если выключить — товар скрыт)</span>
            </label>
        </div>

        <button class="btn" type="submit">Сохранить</button>
    </form>

    <aside class="card image-panel" data-cropper>
        <div>
            <div class="field-label">Фотография товара</div>
            <p class="field-hint">Перетащите изображение мышкой внутри рамки и отрегулируйте масштаб. При сохранении будет отправлена обрезанная версия.</p>
        </div>

        <div class="crop-stage" data-crop-stage>
            <?php
            $currentImageUrl = '';
            if (!empty($product['image_path'])) {
                $currentImageUrl = BASE_URL . '/uploads/' . $product['image_path'];
            }
            $placeholderSvg = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='900' height='675'%3E%3Crect width='100%25' height='100%25' fill='%23f7f3ff'/%3E%3Cpath d='M0 540 L240 330 L420 470 L560 260 L900 520 L900 675 L0 675 Z' fill='%23efe8ff'/%3E%3Ccircle cx='700' cy='220' r='62' fill='%23e5dcff'/%3E%3Ctext x='50%25' y='52%25' text-anchor='middle' font-family='Arial' font-size='34' fill='%23787592'%3EФото товара%3C/text%3E%3C/svg%3E";
            ?>
            <img class="crop-img" data-crop-img src="<?= e($currentImageUrl ?: $placeholderSvg) ?>" alt="">
        </div>

        <div class="crop-controls">
            <div class="file-input">
                <input id="product-image" class="file-input-native" type="file" name="image" accept="image/*" data-crop-file form="product-form">
                <label class="file-btn" for="product-image">Выбрать файл</label>
                <div class="file-name" data-file-name><?= $currentImageUrl ? 'Текущее изображение загружено' : 'Файл не выбран' ?></div>
            </div>

            <div class="range-row">
                <div class="field-label">Масштаб</div>
                <input type="range" min="1" max="3" step="0.01" value="1" data-crop-zoom>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
