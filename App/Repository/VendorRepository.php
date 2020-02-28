<?php


namespace App\Repository;


use App\Model\Vendor;

class VendorRepository extends RepositoryAbstract
{
    protected $model = Vendor::class;

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

}