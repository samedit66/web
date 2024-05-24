<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');

$imported = false;
$error_text = '';
$records_count = 0;
$default_dir = $_SERVER['DOCUMENT_ROOT'] . '/web/files/';
$file_name = '';
$table_name = 'products_imported';

if ('POST' === $_SERVER['REQUEST_METHOD']
        && isset($_POST['action'])
        && $_POST['action'] === 'import') {
    if (!is_dir($default_dir) && !mkdir($default_dir, 0777, true)) {
        $error_text = 'Не удалось создать директорию для загрузки файлов.';
    }
    else {
        $file_name = htmlspecialchars(empty($_POST['file_name']) ? 'products_exported.xml' : $_POST['file_name']);
        list($imported, $records_count, $error_text) = import($default_dir, $file_name);
    }
}

function import(string $dir, string $file_name) : array {
    $file_name = htmlspecialchars($file_name);
    if (!preg_match('/^\w+\.xml$/ui', $file_name)) {
        return [false, 0, "Некорректное имя файла"];
    }

    $file_path = $dir . $file_name;
    if (!file_exists($file_path)) {
        return [false, 0, "Файл не существует"];
    }

    if ('text/xml' != mime_content_type($file_path)) {
        return [false, 0, "Требуется XML файл"];
    }

    $records_count = 0;
    try {
        $records_count = import_data_to_db($file_path);
    }
    catch (InvalidArgumentException $exp) {
        return [false, 0, $exp->getMessage()];
    }

    return [true, $records_count, ''];
}

function import_data_to_db(string $file_path)
{
    $allowed_keys = [
        'id',
        'id_discount',
        'name',
        'description',
        'img_path',
        'cost'
    ];

    // Создаем таблицу в БД, если ее еще не было
    $stmt = Database::prepare('CREATE TABLE IF NOT EXISTS `products_imported` (
        `id` int(10) UNSIGNED NOT NULL,
        `img_path` varchar(45) NOT NULL,
        `name` varchar(45) NOT NULL,
        `id_discount` int(10) UNSIGNED NOT NULL,
        `description` varchar(255) NOT NULL,
        `cost` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;');
    $stmt->execute();

    // Очищаем таблицу в БД
    $stmt = Database::prepare('TRUNCATE products_imported');
    $stmt->execute();

    // Загружаем XML файл
    $xml = simplexml_load_file($file_path);

    $records_count = 0;
    // Загружаем данные из файла в таблицу в БД
    foreach ($xml->children() as $row) {
        $stmt = Database::prepare(
            "INSERT INTO `products_imported` (`id`, `img_path`, `name`, `id_discount`, `description`, `cost`) VALUES (:id, :img_path, :name, :id_discount, :description, :cost)"
        );

        foreach ($row->children() as $key => $value) {
            if (!in_array($key, $allowed_keys)) {
                Database::prepare('TRUNCATE products_imported')->execute();
                throw new InvalidArgumentException("Неизвестный столбец данных");
            }

            $key = (string) $key;
            $value = (string) $value;

            if (is_numeric($value)) {
                $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
            }
            else {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }         
        }

        $stmt->execute();
        $records_count++;
    }

    return $records_count;
}
?>

<div class="m-5 pt-4">
    <?php if ($imported) : ?>
        <?php $file_path = '/web/files/' . $file_name; ?>
        <div class="alert alert-success mb-3" role="alert">
            Файл с данными получен из <a href=<?= $file_path ?>> <?= $file_path ?></a> и обработан.
            Создана таблица <?= $table_name ?>, кол-во записей в таблице: <?= $records_count ?>.
        </div>
    <?php elseif (!empty($error_text)) : ?>
        <div class="alert alert-danger mb-3" role="alert">
            Ошибка: <?= $error_text ?>
        </div>
    <?php endif ?>

    <form method="POST">
        <label for="file_name" class="mb-2">Путь загрузки XML относительно корня сайта</label>
        <input
            name="file_name"
            type="text"
            class="form-control mb-2"
            id="file_name"
            value="<?= $file_name ?>"
            placeholder="products_exported.xml"
            />
        <input class="form-control" type="hidden" name='action' value="import" />
    <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
</form>

</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/footer.php');
