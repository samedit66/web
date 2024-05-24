<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');

$query = "SELECT products.id, products.name, discounts.discount_size, products.description, products.cost, products.img_path
          AS id, name, discount_size, description, cost, img_path
          FROM products
          INNER JOIN discounts ON products.id_discount = discounts.id";
$stmt = Database::prepare($query)
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
            <th scope="col">Описание</th>
            <th scope="col">Доступная скидка (%)</th>
            <th scope="col">Стоимость</th>
            <th scope="col">Изображение</th>
            <th scope="col">Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $item) : ?>
            <tr>
                <th scope="row"><img src="db_images/<?=$item['img_path']?>" style="max-width: 150px;"></th>
                <td> <?=$item['name']?> </td>
                <td> <?=$item['discount']?> </td>
                <td> <?=$item['description']?> </td>
                <td> <?=$item['cost']?> </td>
                <td class="d-flex justify-content-around">
                    <button type="button" class="btn btn-primary">Редактировать</button>
                    <button type="button" class="btn btn-danger">Удалить</button>
                </td>
            </tr>
        <?php endforeach ?>
  </tbody>
</table>

<button type="button" class="btn btn-primary">Добавить</button>

<?php
    require_once('templates/footer.php');
