<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

$folder_id = (int) $_POST['folder_id'] ?? 0;
$name = (string) $_POST['name'] ?? '';

if (!$name) {
    die('Name required');
}

$name = mysqli_real_escape_string($connect, $name);

if ($folder_id) {
    $query = "UPDATE folders SET name = '$name' WHERE id = $folder_id";
} else {
    $query = "INSERT INTO folders(name) VALUES ('$name')";
}

db()->query($query);

header('Location: /folder/');