<?php
class UserLogic
{
    public static function sign_up(string $email, string $password, string $password_confirm, string $full_name,
                                  string $date_of_birth, string $address, string $gender, string $interests,
                                  string $vk, int $blood_type, string $rh_factor) : array | null {
        $errors = [];

        if ($full_name == "") {
            $errors[] = "Введите ФИО";
        }

        if ($interests == "") {
            $errors[] = "Введите интересы";
        }

        if ($address == "") {
            $errors[] = "Введите адрес";
        }

        if ($blood_type < 1 || $blood_type > 4) {
            $errors[] = "Неверно введена группа крови";
        }

        if (!static::check_format($vk, "vk")) {
            $errors[] = "Неверный формат ссылки на вк. Формат ссылки: vk.com/{id}";
        }

        if (UserTable::get_by_email($email) != null) {
            $errors[] = "Пользователь с таким email уже существует";
            return $errors;
        }

        if ($password != $password_confirm) {
            $errors[] = "Пароли не совпадают";
        }

        if (!static::check_format($email, "email")) {
            $errors[] = "Неверный формат email";
        }

        if (!static::check_format($password, "password")) {
            $errors[] = "Пароль должен содержать:  большие латинские буквы, маленькие латинские буквы, " .
            "спецсимволы (знаки препинания, арифметические действия и тп), пробел, дефис, подчеркивание и цифры";
        }

        if (strtotime($date_of_birth) > strtotime(date("Y-m-d"))) {
            $errors[] = "Неверно введена дата";
        }

        if ($errors) {
            return $errors;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        try {
            UserTable::create($email, $password, $full_name, $date_of_birth, $address, $gender, $interests, $vk, $blood_type, $rh_factor);
        } catch (PDOException $e) {
            $errors[] = "При добавлении пользователя возникла ошибка";
        }
        //dfQ-MK3-YHZ-5Pv_ ,+
        return $errors;
    }

    public static function sign_in(string $email, string $password) : array {
        $errors = [];

        if (static::is_authorized()) {
            $errors[] = "Вы уже авторизованы";
            return $errors;
        }

        $user = UserTable::get_by_email($email);
        if (null === $user) {
            $errors[] = "Пользователь с таким email не найден";
            return $errors;
        }

        /*
        if (LoginFailuresLogic::checkBan($email)) {
            return "Вы набрали логин или пароль неверно 3 раза.";
        }
        */

        if (!password_verify($password, $user['password'])) {
            // LoginFailuresLogic::addFail($email);
            $errors[] = "Неверно указан пароль";
            return $errors;
        }

        $_SESSION['USER_ID'] = $user['id'];
        return $errors;
    }

    public static function sign_out() {
        unset($_SESSION['USER_ID']);
    }

    public static function is_authorized() : bool {
        return isset($_SESSION['USER_ID']) && intval($_SESSION['USER_ID']) > 0;
    }

    public static function current() : array | null {
        if(!static::is_authorized()) {
            return null;
        }

        return UserTable::get_by_id($_SESSION['USER_ID']);
    }

    private static function check_password(string $password) : array {
        $length_ok = strlen($password) >= 6;
        $upper_latin_ok = preg_match("/[A-Z]/", $password);
        $lower_latin_ok = preg_match("/[a-z]/", $password);
        $punct_ok = preg_match("/[[:punct:]]/", $password);
        $no_russian_ok = 0 == preg_match("/\p{Cyrillic}/", $password);
        $dash_ok = preg_match("/\-/", $password);
        $underscore_ok = preg_match("/_/", $password);
        $digits_ok = preg_match("/\d/", $password);
        $spaces_ok = preg_match("/\s/", $password);
        $password_ok = $length_ok && $upper_latin_ok && $lower_latin_ok
                    && $punct_ok && $no_russian_ok && $dash_ok
                    && $underscore_ok && $digits_ok && $spaces_ok;
        $result = [
            "password_ok" => $password_ok,
            "length_ok" => $length_ok,
            "upper_latin_ok" => $upper_latin_ok,
            "lower_latin_ok" => $lower_latin_ok,
            "punct_ok" => $punct_ok,
            "no_russian_ok" => $no_russian_ok,
            "dash_ok" => $dash_ok,
            "underscore_ok" => $underscore_ok,
            "digits_ok" => $digits_ok,
            "spaces_ok" => $spaces_ok,
        ];
        return $result;
    } 

    private static function check_email(string $email) : bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) == $email;
    }
    // https://vk.com/samid66
    private static function check_vk(string $vk) : bool {
        return (filter_var($vk, FILTER_VALIDATE_URL) == $vk) && preg_match("/(https:\/\/)?vk.com\/\w+$/", $vk);
    }

    private static function check_format($data, $type) : bool {
        switch ($type) {
            case "email":
                return self::check_email($data);
                break;
            case "password":
                return self::check_password($data)['password_ok']; 
                break;
            case "vk":
                return self::check_vk($data);
                break;
        }

        return false;
    }
}