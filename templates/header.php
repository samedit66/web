<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/web/.core/index.php');
UserActions::sign_out();
?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <title>Глюкометр Акку-Чек Актив/набор/</title>
</head>

<body>

    <header>
        <div class="container-fluid gx-0 fixed-top" style="background: #f5f6fc;">
            <div class="row d-flex py-2 px-3">
                <div class="col-1 d-flex justify-content-center align-items-center">
                    <a href="./index.php" class="d-inline-flex link-body-emphasis text-decoration-none">
                        <img src="img/logo.svg" height="24" alt="Apteka.ru" loading="lazy" />
                    </a>
                </div>

                <div class="col-1 d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-outline-light border-0" aria-label="Выбор города">
                        <img src="img/city.svg" height="24" alt="City" loading="lazy" />
                        <span style="color: #1c257b; font-weight: bold; font-size: 14px;">Москва</span>
                    </button>
                </div>

                <div class="search-bar col-6 d-flex flex-column gap-2">
                    <div class="d-flex align-items-center">
                        <form class="w-100 me-0" role="search">
                            <input type="search" class="form-control rounded-start-pill" style="box-shadow: none !important;" placeholder="Введите название товара, заболевания или симптома" aria-label="Search">
                        </form>
                        <button type="button" class="rounded-end-pill btn btn-primary" style="background: #4665d7;">
                            <span>Искать</span>
                        </button>
                    </div>
                    <div class="d-flex">
                        <span class="ms-2 search-hint">Например: </span>
                        <ul class="nav ms-2">
                            <li>
                                <a href="/search/?q=bioderma" class="example-hint" data-wtag-click="">bioderma</a>
                            </li>
                            <li class="ms-2">
                                <a href="/search/?q=%D1%81%D0%B5%D0%B2%D0%B5%D1%80%D0%BD%D0%B0%D1%8F+%D0%B7%D0%B2%D0%B5%D0%B7%D0%B4%D0%B0" class="example-hint" data-wtag-click="">северная звезда</a>
                            </li>
                            <li class="ms-2">
                                <a href="/search/?q=%D0%B0%D0%BF%D0%B8%D0%BA%D1%81%D0%B0%D0%B1%D0%B0%D0%BD" class="example-hint" data-wtag-click="">апиксабан</a>
                            </li>
                            <li class="ms-2">
                                <a href="/search/?q=%D0%B4%D0%BB%D1%8F+%D0%BB%D0%B5%D1%87%D0%B5%D0%BD%D0%B8%D1%8F+%D0%BC%D0%BE%D0%BB%D0%BE%D1%87%D0%BD%D0%B8%D1%86%D1%8B" class="example-hint" data-wtag-click="">для лечения молочницы</a>
                            </li>
                            <li class="ms-2">
                                <a href="/search/?q=%D0%B7%D0%B0%D1%89%D0%B8%D1%82%D0%B0+%D0%BC%D0%BE%D1%87%D0%B5%D0%B2%D0%BE%D0%B3%D0%BE+%D0%BF%D1%83%D0%B7%D1%8B%D1%80%D1%8F" class="example-hint" data-wtag-click="">защита мочевого пузыря</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-1 d-flex justify-content-center align-items-center">
                    <div class="ButtonIcon">
                        <a class="d-flex flex-column link-underline-light" href="/cart/" aria-label="Корзина">
                            <img src="img/busket.svg" height="24" alt="City" loading="lazy" />
                            <span style="color: #1c257b;">Корзина</span>
                        </a>
                    </div>
                </div>

                <div class="col-3">
                    <?php if (null != UserLogic::current()) : ?>
                        <form method="post">
                            <?= "Вы вошли как " . UserLogic::current()['email'] . "." ?>
                            <input class="form-control" type="hidden" name='action' value="signout" />
                            <button class="btn btn-outline-secondary" type="submit">Выйти</button>
                        </form>
                    <?php else : ?>
                        Вы не авторизованы.
                        <br>
                        <a href="./authorization.php">Ввести логин и пароль</a> или <a href="./registration.php">зарегистрироваться.</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </header>

    <main class="container-fluid gx-0 pt-4" style="margin-top: 74px;">