<?php
    require_once('logic.php');
    require_once('templates/header.php');
?>

<form action="" id="form" method="GET">
    <div class="container  p-3 center ">
        <div class="center w-100  d-flex justify-content-center p-5">Фильтрация результата поиска</div>
        <div class="center w-100  d-flex justify-content-center p-3">По Цене:</div>
        <div class="input-group mb-3 gap10down">
            <input type="text" name="priceFrom" placeholder="Цена от " class="form-control w-100" value="<?php echo isset($_GET['priceFrom']) ? htmlspecialchars($_GET['priceFrom']) : ''; ?>"
                   aria-describedby="inputGroup-sizing-default">
        </div>
        <div class="input-group mb-3 gap10down">
            <input type="text" name="priceTo"  placeholder="Цена до" class="form-control w-100" value="<?php echo isset($_GET['priceTo']) ? htmlspecialchars($_GET['priceTo']) : ''; ?>"
                   aria-describedby="inputGroup-sizing-default">
        </div>
        <div class=" w-100 d-flex justify-content-center p-3">Фильтрация по Работнику:</div>
        <select name="id_worker" type="text" class="form-control w-100" aria-label="Text input with dropdown button">
    <div class="input-group mb-3">
        <option value="">Любой</option>
        <?php
        foreach ($selectA as $items) {
            $selected = ($_GET['id_worker'] == $items['id']) ? 'selected' : '';
            ?>
            <option value="<?php echo $items['id']; ?>" <?php echo $selected; ?>><?php echo $items['name']; ?></option>
            <?php
        }
        ?>
    </select>
 
        <div class="center w-100 d-flex justify-content-center p-3">Фильтрация по описанию</div>
        <input type="text" name="description" placeholder="Введите описание "  value="<?php echo isset($_GET['description']) ? htmlspecialchars($_GET['description']) : ''; ?>"
               class="form-control p-2 w-100"
               aria-describedby="inputGroup-sizing-default">


        <div class="center w-100 d-flex justify-content-center p-3">Фильтрация по названию</div>
        <input type="text" name="name" placeholder="Введите название" class="form-control p-2 w-100" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>"
               aria-describedby="inputGroup-sizing-default">

        <div class="container center d-flex gap-4 p-5 justify-content-center ">
            <button type="submit" class="center btn btn-primary">Применить фильтр</button>
            <button type="submit" class="btn btn-danger" name="clearFilter">Сбросить фильтр</button>
        </div>
        <div></div>
    </div>
</form>

<div class="container text-center mt-3">
    <?php if (count($fullList) > 0):?>
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
            <?php foreach ($fullList as $item):?>
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