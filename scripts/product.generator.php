<?php

use App\Model\Product;
use App\Service\FolderService;
use App\Service\ProductService;
use App\Service\VendorService;

require_once __DIR__ . '/../App/bootstrap.php';

$faker = Faker\Factory::create();
$person = new Faker\Provider\en_US\Person($faker);

for ($i = 0; $i < $faker->numberBetween(400, 500); $i++) {
    $product = new Product();

    $product->setName($person->name());
    $product->setAmount($faker->numberBetween(0, 90));
    $product->setPrice($faker->randomFloat(2, 9, 100));
    $product->setDescription($faker->realText());

    $vendor = VendorService::getRandom();
    $product->setVendorId($vendor->getId());

    for ($j = 0; $j < $faker->numberBetween(1,5); $j++) {
        $folder = FolderService::getRandom();
        $product->addFolderId($folder->getId());
    }

    echo $product->getName() . '\n';

    ProductService::save($product);
}