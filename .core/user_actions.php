<?php

class UserActions
{
    public static function sign_up() : array {
        if ('POST' != $_SERVER['REQUEST_METHOD']) {
            return [];
        }

        if (!empty($_POST['action']) && 'signup' != $_POST['action']) {
            return [];
        }

        $errors = UserLogic::sign_up(
            $_POST['email'],  $_POST['password'],  $_POST['password_confirm'],  $_POST['full_name'],  $_POST['date_of_birth'],
            $_POST['address'], $_POST['gender'],  $_POST['interests'],  $_POST['vk'],  $_POST['blood_type'],
            $_POST['rh_factor']);
        if (!$errors) {
            UserLogic::sign_in($_POST['email'],  $_POST['password']);
            header('Location: index.php');
            die;
        }

        return $errors;
    }

    public static function sign_in() : array {
        if ('POST' != $_SERVER['REQUEST_METHOD']) {
            return [];
        }

        if (!empty($_POST['action']) && 'signin' != $_POST['action']) {
            return [];
        }

        $errors = UserLogic::sign_in($_POST['email'],  $_POST['password']);
        if (!$errors) {
            header('Location: index.php');
            die;
        }

        return $errors;
    }

    public static function sign_out() : string {
        if ('POST' != $_SERVER['REQUEST_METHOD']) {
            return "";
        }

        if (!empty($_POST['action']) && 'signout' != $_POST['action']) {
            return "";
        }

        UserLogic::sign_out();
        header('Location: ' . $_SERVER['PHP_SELF']);
        die;
    }
}
