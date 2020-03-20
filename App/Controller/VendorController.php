<?php


namespace App\Controller;


use App\Http\Response;
use App\Model\Vendor;
use App\Repository\VendorRepository;
use App\Service\RequestService;
use App\Service\VendorService;

class VendorController extends ControllerAbstract
{

    /**
     * @param VendorRepository $vendor_repository
     *
     * @Route(url="/vendor/list")
     *
     * @return Response
     */
    public function list(VendorRepository $vendor_repository) {
        $vendors = $vendor_repository->findAll();

        return $this->render('vendor/index.tpl', ['vendors' => $vendors]);

//        smarty()->assign_by_ref('vendors', $vendors);
//        smarty()->display('vendor/index.tpl');
    }

    /**
     * @param VendorRepository $vendor_repository
     *
     * @Route(url="/vendor/edit")
     * @Route(url="/vendor/edit/{vendor_id}")
     *
     * @return Response
     */
    public function edit(VendorRepository $vendor_repository) {

        $vendor_id = (int) $this->getRoute()->getParam('vendor_id');

        $vendor = $vendor_repository->findOrCreate($vendor_id);

        return $this->render('vendor/edit.tpl', ['vendor' => $vendor,]);
//        if ($vendor_id) {
//
//            $vendor = VendorService::getById($vendor_id);
//
//        } else {
//
//            $vendor = new Vendor();
//        }
//
//        smarty()->assign_by_ref('vendor', $vendor);
//        smarty()->display('vendor/edit.tpl');

    }

    /**
     * @param VendorRepository $vendor_repository
     *
     * @Route(url="/vendor/editing")
     *
     * @return Response
     */
    public function editing(VendorRepository $vendor_repository) {

        $vendor_id = $this->request->getIntFromPost('vendor_id');
        $name = $this->request->getStringFromPost('name');
        $description = $this->request->getStringFromPost('description');

        if (!$name) {
            die('Name required');
        }
        $vendor = $vendor_repository->findOrCreate($vendor_id);

        $name = db()->escape($name);
        $description = db()->escape($description);
//
//        if ($vendor_id) {
//            $vendor = VendorService::getById($vendor_id);
//        } else {
//            $vendor = new Vendor();
//        }

        $vendor->setName($name);
        $vendor->setDescription($description);

        $vendor_repository->save($vendor);

        return $this->redirectToList();
//        RequestService::redirect('/vendor/');
    }

    /**
     * @param VendorRepository $vendor_repository
     *
     * @Route(url="/vendor/delete")
     *
     * @return Response
     */
    public function delete(VendorRepository $vendor_repository) {
        $vendor_id = $this->request->getIntFromPost('vendor_id');

//        if (!$vendor_id) {
//            die('id required');
//        }

        $vendor = $vendor_repository->find($vendor_id);

        $vendor_repository->delete($vendor);

        return $this->redirectToList();
//        RequestService::redirect('vendor/');
    }

    private function redirectToList() {
        return $this->redirect('/vendor/list');
    }


}