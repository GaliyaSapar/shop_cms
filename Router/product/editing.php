<?php

use App\Controller\ProductController;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

ProductController::editing();

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

