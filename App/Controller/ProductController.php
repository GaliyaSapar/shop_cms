<?php


namespace App\Controller;

use App\Model\Product;
use App\Service\FolderService;
use App\Service\ProductService;
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

    public static function edit(int $product_id) {

        $product = new Product();

        if ($product_id) {
            $product = ProductService::getById($product_id);
        }

        $folders = FolderService::getList('id');
        $vendors = VendorService::getList('id');

//        echo '<pre>'; var_dump($product); echo '</pre>';

        smarty()->assign_by_ref('product', $product);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('product/edit.tpl');
    }

}