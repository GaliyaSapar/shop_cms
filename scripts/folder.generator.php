<?php

use App\Model\Folder;
use App\Service\FolderService;

require_once __DIR__ . '/../App/bootstrap.php';

$faker = Faker\Factory::create();
$address = new Faker\Provider\en_US\Address($faker);

for ($i = 0; $i < $faker->numberBetween(10, 30); $i++) {
    $folder = new Folder();
    $folder->setName($address->city());

    echo $folder->getName() . '\n';

    FolderService::save($folder);
}