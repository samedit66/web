<?php

// Подключение к БД
require_once($_SERVER['DOCUMENT_ROOT']) . '/web/.core/db.php';

// Старт сессии
require_once($_SERVER['DOCUMENT_ROOT']) . '/web/.core/session.php';

// Все для работы с "пользователями"
require_once ($_SERVER['DOCUMENT_ROOT'] . '/web/.core/user_table.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/web/.core/user_logic.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/web/.core/user_actions.php');

require_once ($_SERVER['DOCUMENT_ROOT'] . '/web/.core/products_table.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/web/.core/products_logic.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/web/.core/products_actions.php');