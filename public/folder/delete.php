<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

$folder_id = (int) $_POST['folder_id'] ?? 0;

if (!$folder_id) {
    die('id required');
}

$query = "DELETE FROM folders WHERE id = $folder_id";

db()->query($query);

header('Location: /folder/');


