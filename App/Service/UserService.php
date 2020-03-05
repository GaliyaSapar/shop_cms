<?php


namespace App\Service;


use App\Model\User;

class UserService
{
    private function __construct()
    {
    }

    /**
     * @var User;
     */
    private $user;

    private static $salt = 'Fd@6k+7+FmhO';

    public function generatePassHash(string $pass) {
        return $this->md5($this->md5($pass));
    }

    private function md5(string $str) {
        return md5($str . static::$salt);
    }

    public function getCurrentUser(UserRepository $user_repository) {
        $user_id = $_SESSION['user_id'] ?? null;

        if (!($this->user instanceof User)) {
            if (!is_null($user_id)) {
                $this->user = $user_repository->find($user_id);
            } else {
                $this->user = new User();
            }
        }
        return $this->user;
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