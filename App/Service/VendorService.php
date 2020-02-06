<?php

namespace App\Service;

use App\Model\Vendor;

class VendorService
{
    private function __construct()
    {
    }


    /**
     * @param string|null $hash_key
     * @return Vendor[]
     */

    public static function getList(string $hash_key = null) : array {
        $query = 'SELECT * FROM vendors';

        if (is_null($hash_key)) {
            $vendors = db()->fetchAll($query, Vendor::class);
        } else {
            $vendors = db()->fetchAllHash($query, $hash_key, Vendor::class);
        }
        return $vendors;

    }

}