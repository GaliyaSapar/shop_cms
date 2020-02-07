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

        $current_page = RequestService::getIntFromGet('page', 1);
        $per_page = 3;
        $start = $per_page * ($current_page - 1);

//        $products = ProductService::getList('id');

        $products = [
            'count' => ProductService::getCount(),
            'items' => ProductService::getList('id', $start, $per_page)
        ];

        $vendors = VendorService::getlist('id');
        $folders = FolderService::getList('id');

        $paginator = [
            'pages' => ceil($products['count'] / $per_page),
            'current' => $current_page
        ];

        smarty()->assign_by_ref('products',$products);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->assign_by_ref('paginator', $paginator);
        smarty()->display('index.tpl');
    }



    public static function view() {

        $product_id = RequestService::getIntFromGet('product_id');

        $product = ProductService::getById($product_id);

        $folders = FolderService::getList('id');
        $vendors = VendorService::getList('id');

        smarty()->assign_by_ref('product', $product);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('product/view.tpl');
    }

    public static function search() {
        $product_id = RequestService::getIntFromGet('product_id');
        $product_name = RequestService::getStringFromGet('product_name');
        $product_price_from = RequestService::getFloatFromGet('product_price_from');
        $product_price_to = RequestService::getFloatFromGet('product_price_to');


        if ($product_id > 0) {

            $products = ProductService::searchById($product_id);

        } else if ($product_name) {

            $products = ProductService::searchByName($product_name);

        } else if ($product_price_from && $product_price_to) {

            $products = ProductService::searchByPrice($product_price_from, $product_price_to);

        }

        $folders = FolderService::getList('id');
        $vendors = VendorService::getList('id');


        smarty()->assign_by_ref('products', $products);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('product/search.tpl');
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