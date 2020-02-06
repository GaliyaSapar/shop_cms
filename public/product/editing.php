<?php

use App\Model\Product;
use App\Service\ProductService;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

$product_id = $_POST['product_id'] ?? null;
$name = $_POST['name'] ?? null;
$price = $_POST['price'] ?? null;
$amount = $_POST['amount'] ?? null;
$description = $_POST['description'] ?? null;
$vendor_id = $_POST['vendor_id'] ?? null;
$folder_ids = (array) $_POST['folder_ids'] ?? null;

$product_id = (int) $product_id;
$name = (string) $name;
$price = (float) $price;
$amount = (int) $amount;
$description = (string) $description;
$vendor_id = (int) $vendor_id;

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

$product = ProductService::save($product);

//$name = mysqli_real_escape_string($connect, $name); //
//$description = mysqli_real_escape_string($connect, $description);//
//
//if ($product_id) {
//    $query = "UPDATE products SET name = '$name', price = $price, amount = $amount, description = '$description', vendor_id = $vendor_id WHERE id = $product_id";
//} else {
//    $query = "INSERT INTO products (name, price, amount, desctiption, vendor_id) VALUES ('$name', $price, $amount, '$description', $vendor_id)";
//}
//
//db()->query($query);
//
//if (!$product_id) {
//    $product_id = mysqli_insert_id($connect);
//}
//
//$query = "DELETE FROM products_folders WHERE product_id = $product_id";
//db()->query($query);
//
//$data = [];
//
//foreach ($folder_ids as $folder_id) {
//    $data[] = "($product_id, $folder_id)";
//}
//
//if (!empty($data)) {
//    $data = implode(',', $data);
//
//    $query = "INSERT INTO product_folders(product_id, folder_id) VALUES $data";
//    db()->query($query);
//}

header("Location: /");