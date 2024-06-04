<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');

if ('POST' === $_SERVER['REQUEST_METHOD']
        && isset($_POST['action'])
        && $_POST['action'] === 'delete') {
    ProductsTable::delete($_POST["id"]);
}
else {
    echo "SSSSSS";
}

$query = "SELECT products.id AS id, products.name AS name, discounts.discount_size AS discount_size, products.description AS descr, products.cost AS cost, products.img_path AS img_path
          FROM products
          INNER JOIN discounts ON products.id_discount = discounts.id";
$stmt = Database::prepare($query);
$stmt->execute();
$products = [];
while ($row = $stmt->fetch()) {
    $products[] = $row;
}
?>

<table class="table table-bordered mb-3 mt-5">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Название</th>
            <th scope="col">Доступная скидка (%)</th>
            <th scope="col">Описание</th>
            <th scope="col">Стоимость</th>
            <th scope="col">Изображение</th>
            <th scope="col">Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $item) : ?>
            <tr>
                <td> <?=$item['id']?> </td>
                <td> <?=$item['name']?> </td>
                <td> <?=$item['discount_size']?> </td>
                <td> <?=$item['descr']?> </td>
                <td> <?=$item['cost']?> </td>
                <td><img src="db_images/<?=$item['img_path']?>" style="max-width: 150px;"></td>
                <td class="d-flex justify-content-around">
                    <form method="POST">
                        <input class="form-control" type="hidden" name='action' value="edit" />
                        <button type="submit" class="btn btn-primary">Редактировать</button>
                    </form>
                    <form method="POST">
                        <input class="form-control" type="hidden" name="id" value="<?=$item['id']?>" />
                        <input class="form-control" type="hidden" name="action" value="delete" />
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
  </tbody>
</table>

<form method="POST">
    <input class="form-control" type="hidden" name="action" value="add" />
    <button type="submit" class="btn btn-primary">Добавить</button>
</form>

<?php
    require_once('templates/footer.php');
