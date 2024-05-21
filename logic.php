<?php
require_once('db.php');

$query = "SELECT products.img_path, products.name, discounts.discount_size AS discount, products.description, products.cost
        FROM products
        INNER JOIN discounts ON products.id_discount = discounts.id";

$args = [];
$params = [];

if (!isset($_GET['clear_filter'])) {
    if (count($_GET) > 0) {
        if (!empty($_GET['product_name'])) {
            $args[] = "products.name LIKE :name";
            $params[':name'] = '%' . $_GET['product_name'] . '%';
        }
        if (!empty($_GET['description'])) {
            $args[] = "products.description LIKE :description";
            $params[':description'] = '%' . $_GET['description'] . '%';
        }
        if (!empty($_GET['price_from'])) {
            $args[] = "products.cost >= :price_from";
            $params[':price_from'] = $_GET['price_from'];
        }
        if (!empty($_GET['price_to'])) {
            $args[] = "products.cost <= :price_to";
            $params[':price_to'] = $_GET['price_to'];
        }
        if (!empty($_GET['id_discount'])) {
            $args[] = "discounts.id LIKE :id_discount";
            $params[':id_discount'] = '%' . $_GET['id_discount'] . '%';
        }

        if (count($args) > 0) {
            $where_clause = 'WHERE ' . implode(' AND ', $args);
            $query .= ' ' . $where_clause;
        }
    }
} else {
    // Перенаправить пользователя на страницу без параметров фильтрации
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = Database::prepare($query);
foreach ($params as $paramName => $paramValue) {
    if (is_numeric($paramValue)) {
        $stmt->bindValue($paramName, $paramValue, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($paramName, $paramValue, PDO::PARAM_STR);
    }
}
$stmt->execute();

$products = [];
while ($row = $stmt->fetch()) {
    $products[] = $row;
}

$discounts = [];
$query = "SELECT * FROM discounts";
$stmt = Database::prepare($query);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $discounts[] = $row;
}
?>
