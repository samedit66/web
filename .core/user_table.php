<?php
class UserTable
{
    public static function create(
        string $email, string $password, string $full_name, string $date_of_birth, string $address, string $gender,
        string $interests, string $vk, int $blood_type, string $rh_factor
    )
    {
        $query = Database::prepare(
            'INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `date_of_birth`, `address`, 
                                            `gender`, `interests`, `vk`, `blood_type`, `rh_factor`) ' .
            'VALUES (NULL, :email, :password, :full_name, :date_of_birth, :address, :gender, :interests, :vk, 
                    :blood_type, :rh_factor)');
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);
        $query->bindValue(':full_name', $full_name);
        $query->bindValue(':date_of_birth', $date_of_birth);
        $query->bindValue(':address', $address);
        $query->bindValue(':gender', $gender);
        $query->bindValue(':interests', $interests);
        $query->bindValue(':vk', $vk);
        $query->bindValue(':blood_type', $blood_type);
        $query->bindValue(':rh_factor', $rh_factor);

        if(!$query->execute()) {
            throw new PDOException("При добавлении пользователя возникла ошибка");
        }
    }

    public static function get_by_email(string $email) : array | null {
        $query = Database::prepare(
            'SELECT * FROM `users` WHERE `email` = :email LIMIT 1');
        $query->bindValue(':email', $email);
        $query->execute();

        $users = $query->fetchAll();

        if (!count($users)) {
            return null;
        }

        return $users[0];
    }

    public static function get_by_id(int $id) {
        $query = Database::prepare("SELECT * FROM `users` WHERE id = :id");
        $query->execute(array(":id" => $id));
        return $query->fetch();
    }
}
