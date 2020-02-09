<?php


namespace App\Controller;


use App\Model\User;
use App\Service\RequestService;
use App\Service\UserService;

class UserController
{
    private function __construct()
    {
    }

    public static function login() {
        $login = RequestService::getStringFromPost('login');
        $pass = RequestService::getStringFromPost('pass');

        /**
         * @var $user User
         */
        $user = UserService::getUserByName($login);

        $error_msg = 'User not found or data is incorrect';

        if (is_null($user)) {
            echo $error_msg;
            exit;
        }

        $pass = UserService::generatePassHash($pass);

        if ($user->getPass() !== $pass) {
            echo $error_msg;
            exit;
        }

        $_SESSION['user_id'] = $user->getId();

        RequestService::redirect('/');
    }


    public static function logout() {
        unset($_SESSION['user_id']);
        RequestService::redirect('/');
    }

    public static function edit() {
        $user = user();

        smarty()->assign_by_ref('user', $user);
        smarty()->display('user/edit.tpl');
    }

    public static function editing() {

        $user = user();

        $name = RequestService::getStringFromPost('name');
        $email = RequestService::getStringFromPost('email');
        $password = RequestService::getStringFromPost('password');
        $password_repeat = RequestService::getStringFromPost('password_repeat');

        if ($password !== $password_repeat) {
            die('Passwords mismatch');
        }

        $is_email_exist = UserService::isValueExist('email', $email);
        $is_login_exist = UserService::isValueExist('name', $name);

        if($user->getId()) {
            $current_login = $user->getName();
            $current_email = $user->getEmail();

            if (($current_login !== $name) && $is_login_exist) {
                die('login is busy!');
            }
            if (($current_email !== $email) && $is_email_exist) {
                die('email is busy!');
            }

        } else {
            if ($is_login_exist) {
                die('login is busy!');
            }
            if ($is_email_exist) {
                die('email is busy!');
            }
        }

        $password = UserService::generatePassHash($password);

        $user->setEmail($email);
        $user->setName($name);
        $user->setPass($password);

        if (!$user->getId()) {
            mail($email, 'Вы успешно зарегистрировались', 'Вы зарегистрировались как: ' . $name);
        }

        UserService::save($user);

        RequestService::redirect('/');
    }
}