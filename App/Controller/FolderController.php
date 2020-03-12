<?php


namespace App\Controller;


use App\Http\Response;
use App\Model\Folder;
use App\Repository\FolderRepository;
use App\Service\FolderService;
use App\Service\RequestService;
use GivenClassNotImplementerITableRowException;
use QueryException;

class FolderController extends ControllerAbstract
{
    /**
     * @param FolderRepository $folder_repository
     * @return Response
     *
     * @Route(url='/folder/list')
     *
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function list(FolderRepository $folder_repository) {
        $folders = $folder_repository->findAll();

        return $this->render('folder/index.tpl', ['folders' => $folders,]);

//        smarty()->assign_by_ref('folders', $folders);
//        smarty()->display('folder/index.tpl');
    }

    /**
     * @param FolderRepository $folder_repository
     * @return Response
     *
     * @Route(url='/folder/edit')
     * @Route(url='/folder/edit/{folder_id}')
     *
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function edit(FolderRepository $folder_repository) {

        $folder_id = (int) $this->getRoute()->getParam('folder_id');

        $folder = $folder_repository->findOrCreate($folder_id);

        return $this->render('folder/edit.tpl', ['folder' => $folder]);
//        if ($folder_id) {
//
//            $folder = FolderService::getById($folder_id);
//
//        } else {
//
//            $folder = new Folder();
//        }
//
//        smarty()->assign_by_ref('folder', $folder);
//        smarty()->display('folder/edit.tpl');

    }

    /**
     * @param FolderRepository $folder_repository
     * @return Response
     *
     * @Route(url='/folder/editing')
     *
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function editing(FolderRepository $folder_repository) {

        $folder_id = (int) $this->request->getIntFromPost('folder_id');
        $name = $this->request->getStringFromPost('name');

        if (!$name) {
            die('Name required');
        }

        $folder = $folder_repository->findOrCreate($folder_id);

        $name = db()->escape($name);
//
//        if ($folder_id) {
//            $folder = FolderService::getById($folder_id);
//        } else {
//            $folder = new Folder();
//        }

        $folder->setName($name);

        $folder_repository->save($folder);

        return $this->redirectToList();
    }

    /**
     * @param FolderRepository $folder_repository
     *
     * @Route(url='/folder/delete')
     *
     * @return Response
     *
     * @throws GivenClassNotImplementerITableRowException
     * @throws QueryException
     */
    public function delete(FolderRepository $folder_repository) {
        $folder_id = (int) $this->request->getIntFromPost('folder_id');

//        if (!$folder_id) {
//            die('id required');
//        }

        $folder = $folder_repository->find($folder_id);

        $folder_repository->delete($folder);

        return $this->redirectToList();
    }

    private function redirectToList() {
        return $this->redirect('/folder/list');
    }

}