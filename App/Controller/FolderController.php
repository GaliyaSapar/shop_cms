<?php


namespace App\Controller;


use App\Model\Folder;
use App\Service\FolderService;
use App\Service\RequestService;

class FolderController
{
    private function __construct()
    {
    }

    public function list() {
        $folders = FolderService::getList('id');

        smarty()->assign_by_ref('folders', $folders);
        smarty()->display('folder/index.tpl');
    }

    public function edit() {

        $folder_id = RequestService::getIntFromGet('folder_id');

        if ($folder_id) {

            $folder = FolderService::getById($folder_id);

        } else {

            $folder = new Folder();
        }

        smarty()->assign_by_ref('folder', $folder);
        smarty()->display('folder/edit.tpl');

    }

    public function editing() {

        $folder_id = RequestService::getIntFromPost('folder_id');
        $name = RequestService::getStringFromPost('name');

        if (!$name) {
            die('Name required');
        }

        $name = db()->escape($name);

        if ($folder_id) {
            $folder = FolderService::getById($folder_id);
        } else {
            $folder = new Folder();
        }

        $folder->setName($name);

        FolderService::save($folder);

        RequestService::redirect('/folder/');
    }

    public function delete() {
        $folder_id = RequestService::getIntFromPost('folder_id');

        if (!$folder_id) {
            die('id required');
        }

        $folder = FolderService::getById($folder_id);

        FolderService::delete($folder);

        RequestService::redirect('folder/');
    }

}