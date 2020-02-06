<?php


namespace App\Controller;

use App\Model\Product;
use App\Service\FolderService;
use App\Service\ProductService;
use App\Service\RequestService;
use App\Service\VendorService;

class ProductController
{

    private function __construct()
    {
    }

    public static function list() {

        $products = ProductService::getList('id');
        $vendors = VendorService::getlist('id');
        $folders = FolderService::getList('id');

        smarty()->assign_by_ref('products',$products);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('index.tpl');
    }

    public static function view() {

        $product_id = RequestService::getIntFromGet('product_id');

        $product = ProductService::getById($product_id);

        $folders = FolderService::getList('id');
        $vendors = VendorService::getList('id');

//        echo '<pre>'; var_dump($product); echo '</pre>';

        smarty()->assign_by_ref('product', $product);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('product/view.tpl');
    }

    public static function edit() {
        $product_id = RequestService::getIntFromGet('product_id');

        if ($product_id) {
           $product = ProductService::getById($product_id);
        } else {
            $product = new Product();
        }

        $folders = FolderService::getList('id');
        $vendors = VendorService::getList('id');

        smarty()->assign_by_ref('product', $product);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('product/edit.tpl');
    }

    public static function editing() {

        $product_id = RequestService::getIntFromPost('product_id');
        $name = RequestService::getStringFromPost('name');
        $price = RequestService::getFloatFromPost('price');
        $amount = RequestService::getIntFromPost('amount');
        $description = RequestService::getStringFromPost('description');
        $vendor_id = RequestService::getIntFromPost('vendor_id');
        $folder_ids = RequestService::getArrayFromPost('folder_ids');

        if (!$name || !$price || !$amount) {
            die('not enough data');
        }

        $product = new Product();

        if($product_id) {
            $product = ProductService::getById($product_id); //?нужен только id, setId нет. mysqli_fetch_object может иниц private поля
        }

        $product->setName($name);
        $product->setPrice($price);
        $product->setAmount($amount);
        $product->setDescription($description);
        $product->setVendorId($vendor_id);

        $product->removeAllFolders();

        foreach ($folder_ids as $folder_id) {
            $product->addFolderId($folder_id);
        }

        ProductService::save($product);

        RequestService::redirect('/');
    }

}