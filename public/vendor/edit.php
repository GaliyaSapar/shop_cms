<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

//echo '<pre>'; var_dump($_SERVER['DOCUMENT_ROOT']); echo '</pre>';

$vendor_id = (int) $_GET['vendor_id'] ?? 0;

if ($vendor_id) {
    $query = "SELECT * FROM vendors WHERE id = $vendor_id";
    $result = db()->query($query);

    $vendor = mysqli_fetch_assoc($result);



    smarty()->assign_by_ref('vendor', $vendor);

    echo '<pre>'; var_dump($vendor); echo '</pre>';
}

smarty()->display('vendor/edit.tpl');
