<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');

$sign_up_errors = UserActions::sign_up();

$email = $_POST['email'] ?? '';
$full_name = $_POST['full_name'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$address = $_POST['address'] ?? '';
$gender = $_POST['gender'] ?? '';
$interests = $_POST['interests'] ?? '';
$vk = $_POST['vk'] ?? '';
$blood_type = $_POST['blood_type'] ?? '';
$rh_factor = $_POST['rh_factor'] ?? '';

require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/header.php');
?>

<?php if (null != UserLogic::current()) : ?>
    <div class="px-4 py-5 my-5 text-center">
        <form method="post">
            <h1 class="display-5">
                <?= "Вы уже авторизованы как " . UserLogic::current()['email'] . "." ?>
            </h1>
            <input class="form-control" type="hidden" name='action' value="signout" />
            <button class="btn btn-outline-secondary" type="submit">Выйти</button>
        </form>
    </div>
<?php else : ?>
    <div class="text-center">
        <div class="form-signin">
            <form method="post">
                <h1 class="h3 mb-3 fw-normal">Регистрация</h1>

                <?php foreach ($sign_up_errors as $err) : ?>
                    <div class="alert alert-danger">
                        <?= $err ?>
                    </div>
                <?php endforeach ?>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="text" id="form3Example1cg" class="form-control" name="full_name" value="<?= htmlspecialchars($full_name) ?>" />
                    <label class="form-label" for="form3Example1cg">ФИО</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="email" id="form3Example3cg" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" />
                    <label class="form-label" for="form3Example3cg">Email</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="password" id="form3Example4cg" class="form-control" name="password" />
                    <label class="form-label" for="form3Example4cg">Пароль</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="password" id="form3Example4cdg" class="form-control" name="password_confirm" />
                    <label class="form-label" for="form3Example4cdg">Повторите пароль</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <select class="form-select" name="gender">
                        <option value="M" <?php if ($gender == "M") echo "selected" ?>>Мужчина</option>
                        <option value="F" <?php if ($gender == "F") echo "selected" ?>>Женщина</option>
                    </select>
                    <label class="form-label" for="form3Example4cdg">Пол</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="date" id="form3Example1cg" class="form-control" name="date_of_birth" value="<?= $date_of_birth ?>" />
                    <label class="form-label" for="form3Example1cg">Дата рождения</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="text" id="form3Example1cg" class="form-control" name="address" value="<?= $address ?>" />
                    <label class="form-label" for="form3Example1cg">Адрес</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="text" id="form3Example1cg" class="form-control" name="interests" value="<?= htmlspecialchars($interests) ?>" />
                    <label class="form-label" for="form3Example1cg">Интересы</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <input type="text" id="form3Example1cg" class="form-control" name="vk" value="<?= htmlspecialchars($vk) ?>" />
                    <label class="form-label" for="form3Example1cg">Ссылка на вк</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <select class="form-select" name="blood_type">
                        <option value="1" <?php if ($blood_type == "1") echo "selected" ?>>1</option>
                        <option value="2" <?php if ($blood_type == "2") echo "selected" ?>>2</option>
                        <option value="3" <?php if ($blood_type == "3") echo "selected" ?>>3</option>
                        <option value="4" <?php if ($blood_type == "4") echo "selected" ?>>4</option>
                    </select>
                    <label class="form-label" for="form3Example4cdg">Группа крови</label>
                </div>

                <div class="form-floating mb-4 mx-auto col-sm-4">
                    <select class="form-select" name="rh_factor">
                        <option value="POSITIVE" <?php if ($rh_factor == "POSITIVE") echo "selected" ?>>Положительный
                        </option>
                        <option value="NEGATIVE" <?php if ($rh_factor == "NEGATIVE") echo "selected" ?>>Отрицательный
                        </option>
                    </select>
                    <label class="form-label" for="form3Example4cdg">Резус фактор</label>
                </div>

                <input class="form-control" type="hidden" name='action' value="signup" />
                <button class="mx-auto col-sm-4 btn btn-lg btn-primary" type="submit">Зарегистрироваться</button>
            </form>

            <div class="checkbox mb-3">
                <label>
                    <br>У вас уже есть учетная запись? <a href="./authorization.php">Войти.</a>
                </label>
            </div>

        </div>
    </div>
<?php endif ?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/footer.php');
