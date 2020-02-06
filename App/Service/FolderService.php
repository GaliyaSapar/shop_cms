<?php


namespace App\Service;


use App\Model\Folder;

class FolderService
{
    private function __construct()
    {
    }

    /**
     * @param string|null $hash_key
     * @return Folder[]
     */

    public static function getList(string $hash_key = null) : array {
        $query = 'SELECT * FROM folders';

        if (is_null($hash_key)) {
            $folders = db()->fetchAll($query, Folder::class);
        } else {
            $folders = db()->fetchAllHash($query, $hash_key, Folder::class);
        }
        return $folders;

    }

}