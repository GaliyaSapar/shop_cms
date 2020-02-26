<?php


namespace App\Service;


use App\Model\Folder;

class FolderService
{
    public function __construct()
    {
    }

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

    public function getById(int $folder_id) {
        $query = "SELECT * FROM folders WHERE id = $folder_id";

        $folder = db()->fetchRow($query, Folder::class);

        return $folder;
    }

    public function delete(Folder $folder) {

        db()->delete('folders', ['id' => $folder->getId()]);

        return true;
    }

    public function save(Folder $folder) {
        $folder_id = $folder->getId();

        if ($folder_id > 0) {
            db()->update('folders', ['name' => $folder->getName()], ['id' => $folder_id] );
        } else {
            db()->insert('folders', ['name' => $folder->getName()]);
        }
    }

    /**
     * @return Folder|null
     */
    public function getRandom() {
        $query = "SELECT * FROM folders ORDER BY RAND() LIMIT 1";
        return db()->fetchRow($query, Folder::class);
    }

}