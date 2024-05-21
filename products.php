<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');

    if (null === UserLogic::current()) {
        header('Location: authorization.php');
        die;
    }

    require_once('logic.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');
?>

<form action="" id="form" method="GET">
    <div class="container  p-3 center ">
        <div class="center w-100  d-flex justify-content-center p-5">Фильтрация результата поиска</div>

        <div class="center w-100  d-flex justify-content-center p-3">По названию препарата:</div>
        <div class="input-group mb3 gap10down">
            <input
                type="text"
                name="product_name"
                placeholder="Название препарата"
                class="form-control w-100"
                value="<?php echo isset($_GET['product_name']) ? htmlspecialchars($_GET['product_name']) : ''; ?>"
                >
        </div>

        <div class="center w-100  d-flex justify-content-center p-3">Фильтрация по цене:</div>
        <div class="input-group mb-3 gap10down">
            <input
                type="text"
                name="price_from"
                placeholder="Цена от"
                class="form-control w-100"
                value="<?php echo isset($_GET['price_from']) ? htmlspecialchars($_GET['price_from']) : ''; ?>"
                aria-describedby="inputGroup-sizing-default"
                >
        </div>
        <div class="input-group mb-3 gap10down">
            <input
                type="text"
                name="price_to" 
                placeholder="Цена до"
                class="form-control w-100"
                value="<?php echo isset($_GET['price_to']) ? htmlspecialchars($_GET['price_to']) : ''; ?>"
                aria-describedby="inputGroup-sizing-default">
        </div>

        <div class="w-100 d-flex justify-content-center p-3">Фильтрация по скидке (%):</div>
        <select
            name="id_discount"
            type="text"
            class="form-control w-100"
            aria-label="Text input with dropdown button"
            >

            <div class="input-group mb-3">
                <option value="">Любая</option>
                <?php
                    foreach ($discounts as $discount) {
                        $selected = ($_GET['id_discount'] == $discount['id']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $discount['id']; ?>" <?php echo $selected; ?>>
                        <?php echo $discount['discount_size']; ?>
                    </option>
                <?php
                    }
                ?>
        </select>
 
        <div class="center w-100 d-flex justify-content-center p-3">Фильтрация по описанию препарата</div>
        <input
            type="text"
            name="description"
            placeholder="Описание препарата" 
            value="<?php echo isset($_GET['description']) ? htmlspecialchars($_GET['description']) : ''; ?>"
            class="form-control p-2 w-100"
            aria-describedby="inputGroup-sizing-default"
            >

        <div class="container center d-flex gap-4 p-5 justify-content-center ">
            <button type="submit" class="center btn btn-primary">Применить фильтр</button>
            <button type="submit" class="btn btn-danger" name="clear_filter">Сбросить фильтр</button>
        </div>
        <div></div>
    </div>
</form>

<div class="container text-center mt-3">
    <?php if (count($products) > 0):?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Изображение</th>
                    <th scope="col">Название</th>
                    <th scope="col">Доступная скидка (%)</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Стоимость</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $item):?>
                <tr>
                    <th scope="row"><img src="db_images/<?=$item['img_path']?>" style="max-width: 150px;"></th>
                    <td><?=$item['name']?></td>
                    <td><?=$item['discount']?></td>
                    <td><?=$item['description']?></td>
                    <td><?=$item['cost']?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif;?>
</div>

<?php
    require_once('templates/footer.php');
?>