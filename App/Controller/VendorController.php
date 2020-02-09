<?php


namespace App\Controller;


use App\Model\Vendor;
use App\Service\RequestService;
use App\Service\VendorService;

class VendorController
{
    private function __construct()
    {
    }

    public function list() {
        $vendors = VendorService::getList('id');

        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('vendor/index.tpl');
    }

    public function edit() {

        $vendor_id = RequestService::getIntFromGet('vendor_id');

        if ($vendor_id) {

            $vendor = VendorService::getById($vendor_id);

        } else {

            $vendor = new Vendor();
        }

        smarty()->assign_by_ref('vendor', $vendor);
        smarty()->display('vendor/edit.tpl');

    }

    public function editing() {

        $vendor_id = RequestService::getIntFromPost('vendor_id');
        $name = RequestService::getStringFromPost('name');
        $description = RequestService::getStringFromPost('description');

        if (!$name) {
            die('Name required');
        }

        $name = db()->escape($name);
        $description = db()->escape($description);

        if ($vendor_id) {
            $vendor = VendorService::getById($vendor_id);
        } else {
            $vendor = new Vendor();
        }

        $vendor->setName($name);
        $vendor->setDescription($description);

        VendorService::save($vendor);

        RequestService::redirect('/vendor/');
    }

    public function delete() {
        $vendor_id = RequestService::getIntFromPost('vendor_id');

        if (!$vendor_id) {
            die('id required');
        }

        $vendor = VendorService::getById($vendor_id);

        VendorService::delete($vendor);

        RequestService::redirect('vendor/');
    }

}