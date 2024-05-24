<?php
class ProductsTable
{
    public static function create(string $name, string $description, int $id_discount, string $img_path, int $cost)
    {
        $query = Database::prepare(
            "INSERT INTO `products` (`id`, `img_path`, `name`, `id_discount`, `description`, `cost`) VALUES (:img_path, :name, :id_discount, :description, :cost)");
        $query->bindValue(':img_path', $img_path, PDO::PARAM_STR);
        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->bindValue(':id_discount', $id_discount, PDO::PARAM_INT);
        $query->bindValue(':description', $description, PDO::PARAM_STR);
        $query->bindValue(':cost', $cost, PDO::PARAM_INT);

        if (!$query->execute()) {
            throw new PDOException("При добавлении пользователя возникла ошибка");
        }
    }

    public static function get_all() : array {
        $query = Database::prepare('SELECT * FROM `products`');
        $query->execute();
        return $query->fetchAll();
    }

    public static function get_by_name(string $name) : array | null {
        $query = Database::prepare(
            'SELECT * FROM `products` WHERE `name` = :name LIMIT 1');
        $query->bindValue(':name', $name);
        $query->execute();

        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!count($users)) {
            return null;
        }

        return $users[0];
    }

    public static function get_by_id(int $id) {
        $query = Database::prepare("SELECT * FROM `products` WHERE id = :id");
        $query->execute(array(":id" => $id));
        return $query->fetch();
    }
}
