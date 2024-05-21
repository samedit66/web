<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');

$sign_in_errors = UserActions::sign_in();

$email = $_POST['email'] ?? '';

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
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Вход в систему</h1>

            <?php foreach ($sign_in_errors as $err) : ?>
                <div class="alert alert-danger">
                    <?= $err ?>
                </div>
            <?php endforeach ?>

            <div class="mb-3 mx-auto col-sm-4 form-floating">
                <input type="email"
                       class="form-control"
                       id="floatingInput"
                       placeholder="name@example.com"
                       name="email"
                       value="<?= htmlspecialchars($email) ?>">
                <label for="floatingInput">Email</label>
            </div>
            <div class="mb-3 mx-auto col-sm-4 form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                <label for="floatingPassword">Пароль</label>
            </div>

            <input class="form-control" type="hidden" name='action' value="signin" />
            <button class="mx-auto col-sm-4 btn btn-lg btn-primary" type="submit">Войти</button>
        </form>

        <div class="checkbox mb-3">
            <label>
                <br>
                Новый пользователь?
                <a href="./registration.php">Зарегистрироваться.</a>
            </label>
        </div>
    </div>
<?php endif ?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/templates/footer.php');
