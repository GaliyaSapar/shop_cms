<?php


namespace App\Repository;


use App\Model\Folder;

class FolderRepository extends RepositoryAbstract
{

    protected $model = Folder::class;

    /**
     * @param string|null $hash_key
     * @return Folder[]
     */

    public function getList(string $hash_key = null) : array {
        $query = 'SELECT * FROM folders';

        if (is_null($hash_key)) {
            $folders = db()->fetchAll($query, Folder::class);
        } else {
            $folders = db()->fetchAllHash($query, $hash_key, Folder::class);
        }
        return $folders;

    }

}