<?php


namespace App\Service;


use App\Model\User;

class UserService
{
    private function __construct()
    {
    }

    private static $salt = 'Fd@6k+7+FmhO';

    public static function generatePassHash(string $pass) {
        return static::md5(static::md5($pass));
    }

    private static function md5(string $str) {
        return md5($str . static::$salt);
    }

    public static function isValueExist(string $fieldname, string $value) {
        $value = db()->escape($value);
        $query = "SELECT * FROM users WHERE $fieldname = '$value'";
        

        $result = db()->fetchRow($query, User::class);

        return !is_null($result);
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

    public static function save(User $user) {

        $user_id = $user->getId();

        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'pass' => $user->getPass(),
        ];

        if ($user_id) {

            db()->update('users', $data, ['id' => $user_id]);
        } else {
            db()->insert('users', $data);
        }

    }


}