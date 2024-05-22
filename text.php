<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/text_utils.php');

$text = "";
if (isset($_GET['preset'])) {
    $preset = intval($_GET['preset']);
    switch ($preset) {
        case 1:
            $text = file_get_contents("resources/preset_1.html");
            break;
        case 2:
            $text = file_get_contents("resources/preset_2.html");
            break;
        case 3:
            $text = file_get_contents("resources/preset_3.html");
            break;
    }
}
elseif (isset($_POST['input'])) {
    $text = $_POST['input'];
}

$images = extract_images($text);
$corrected_text = correct_reductions($text);
list($tables, $tables_html) = tables_pointer($text);
list($styles, $styles_html) = simplify_repeated_styles($text);
?>

<div class="mx-4 pt-4">
    <form class="mb-3 d-flex flex-column align-items-center" method="POST">
        <label for="exampleFormControlTextarea1" class="form-label">Вставьте HTML код</label>
        <textarea class="form-control mb-3" id="exampleFormControlTextarea1" rows="20" name="input"><?= htmlspecialchars($text) ?></textarea>
        <button type="submit" class="btn btn-primary mb-3">Отправить</button>
    </form>

    <?php if ($images) : ?>    
        <div class="mb-3">
            <h4>Найденные изображения (задание 2):</h4>
            <?php foreach (extract_images($text) as $image) : ?>
                <?= $image ?>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php if ($corrected_text) : ?>
        <div class="mb-3">
            <h4>Отредактированный текст (задание 8):</h4>
            <?= $corrected_text ?>
        </div>
    <?php endif ?>

    <?php if ($tables) : ?>    
        <div class="mb-3">
            <h4>Найденные таблицы (задание 12):</h4>

            <?php foreach ($tables as $index => $table) : ?>
                <div>
                    <a href="#<?= $table['id'] ?>">Таблица <?= $index + 1 ?>: <?= $table['first_cell_data'] ?></a>
                </div>
            <?php endforeach ?>

            <div><?= $tables_html ?></div>
        </div>
    <?php endif ?>

    <?php if ($styles) : ?>
        <div class="mb-3">
            <h4>Стили (задание 20):</h4>

            <?php
                echo '<style type="text/css">';
                foreach ($styles as $style => $class_name) {
                    echo "$class_name {";
                    echo $style;
                    echo "}";
                }
                echo '</style>';
            ?>

            <?= $styles_html ?>
        </div>
    <?php endif ?>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/footer.php');
