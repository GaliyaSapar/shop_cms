<?php

namespace App\Service;

use App\Model\Vendor;

class VendorService
{
    public function __construct()
    {
    }


    /**
     * @param string|null $hash_key
     * @return Vendor[]
     */

    public function getList(string $hash_key = null) : array {
        $query = 'SELECT * FROM vendors';

        if (is_null($hash_key)) {
            $vendors = db()->fetchAll($query, Vendor::class);
        } else {
            $vendors = db()->fetchAllHash($query, $hash_key, Vendor::class);
        }
        return $vendors;

    }

    public function getById(int $vendor_id) {
        $query = "SELECT * FROM vendors WHERE id = $vendor_id";

        $vendor = db()->fetchRow($query, Vendor::class);

        return $vendor;
    }

    public function delete(Vendor $vendor) {

        db()->delete('vendors', ['id' => $vendor->getId()]);

        return true;
    }

    public function save(Vendor $vendor) {
        $vendor_id = $vendor->getId();

        if ($vendor_id > 0) {
            db()->update('vendors', ['name' => $vendor->getName(), 'description' => $vendor->getDescription()], ['id' => $vendor_id] );
        } else {
            db()->insert('vendors', ['name' => $vendor->getName(), 'description'=> $vendor->getDescription()]);
        }
    }

    /**
     * @return Vendor|null
     */

    public function getRandom() {
        $query = "SELECT * FROM vendors ORDER BY RAND() LIMIT 1";
        return db()->fetchRow($query, Vendor::class);
    }

}