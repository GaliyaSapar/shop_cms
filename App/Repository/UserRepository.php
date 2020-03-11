<?php


namespace App\Repository;


use App\Model\User;

/**
 * Class UserRepository
 * @package App\Repository
 *
 * @method User find(int $id)
 * @method User[] findAll()
 * @method User[] findAllWithLimit(int $limit = 50, int $start = 0)
 */

class UserRepository extends RepositoryAbstract
{
    protected $model = User::class;

    public function findByName(string $name): User {
        $name = $this->odm->escape($name);

        $query = "SELECT * FROM users WHERE name = '$name'";

        return $this->odm->fetchRow($query, $this->model);
    }

}