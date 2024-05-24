<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');

$exported = false;
$error_text = '';
$default_dir = $_SERVER['DOCUMENT_ROOT'] . '/web/files/';
$file_name = '';

if ('POST' === $_SERVER['REQUEST_METHOD']
        && !empty($_POST['action'])
        && 'export' === $_POST['action']) {
    if (!is_dir($default_dir) && !mkdir($default_dir, 0777, true)) {
        $error_text = 'Не удалось создать директорию для загрузки файлов.';
    }
    else {
        $file_name = htmlspecialchars(empty($_POST['file_name']) ? 'products_exported.xml' : $_POST['file_name']);
        list($exported, $error_text) = export($default_dir, $file_name);
    }
}

function export(string $dir, string $file_name): array {
    if (!preg_match('/^\w+\.xml$/u', $file_name)) {
        return [false, "Некорректное имя файла"];
    }
    $stmt = Database::get_instance()->prepare('SELECT * FROM products');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $xmlstr = '<?xml version="1.0" encoding="UTF-8"?><products></products>';
    $xml = new SimpleXMLElement($xmlstr);
    foreach ($products as $product) {
        $product_xml = $xml->addChild('product');
        $product_xml->addChild('id', $product['id']);
        $product_xml->addChild('id_discount', $product['id_discount']);
        $product_xml->addChild('name', $product['name']);
        $product_xml->addChild('description', $product['description']);
        $product_xml->addChild('img_path', $product['img_path']);
        $product_xml->addChild('cost', $product['cost']);
    }
    
    $xml->asXml($dir . $file_name);
    
    return [true, ''];
}
?>

<div class="m-5 pt-4">
    <?php if ($exported) : ?>
        <?php $file_path = $default_dir . $file_name; ?>
        <div class="alert alert-success mb-3" role="alert">
            Файл с данными сохранен на диск по адресу: 
            <a href=<?= $file_path ?>> <?= $file_path ?></a>
        </div>
    <?php elseif (!empty($error_text)) : ?>
        <div class="alert alert-danger mb-3" role="alert">
            Ошибка: <?= $error_text ?>
        </div>
    <?php endif ?>

    <form method="POST">
        <label for="file_name" class="mb-2">Путь сохранения XML относительно корня сайта</label>
        <input
            name="file_name"
            type="text"
            class="form-control mb-2"
            id="file_name"
            value="<?= $file_name ?>"
            placeholder="products_exported.xml"
            />
        <input class="form-control" type="hidden" name='action' value="export" />
        <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
    </form>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/footer.php');
