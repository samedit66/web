<?php
$host = 'localhost';
$db = 'pharmacy';
$password = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // подключения к базе данных
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // настройка для исключений
    PDO::ATTR_STRINGIFY_FETCHES => false,
    PDO::ATTR_EMULATE_PREPARES => false
];

$pdo = new PDO($dsn, $user, $password, $options); // пдо-драйвер для подключения к базе данных
