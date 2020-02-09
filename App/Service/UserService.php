<?php


namespace App\Service;


use App\Model\User;

class UserService
{
    private function __construct()
    {
    }

    public static function getUserByName(string $username) {

        $username = db()->escape($username);

        $query = "SELECT * FROM users WHERE name = '$username'";

        return db()->fetchRow($query, User::class);
    }

    public static function getUserById(int $user_id) {
        $query = "SELECT * FROM users WHERE id = $user_id";

        return db()->fetchRow($query, User::class);
    }

}