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

        if ($user->getPass() !== $pass) {
            echo $error_msg;
            exit;
        }
        $_SESSION['user_id'] = $user->getId();

        RequestService::redirect('/');
    }

}