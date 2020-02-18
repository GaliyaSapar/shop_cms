<?php


namespace App\Service;


use App\Model\Folder;
use App\Model\Model;
use App\Model\Product;

class ProductService
{

    private function __construct()
    {
    }


    /**

     * @param string|null $hash_key
     * @param integer $start
     * @param integer $limit
     * @return Product[]
     */

    public static function getList(string $hash_key = null, int $start = 0, int $limit = 100) : array {

        $query = "SELECT * FROM products";

//        if ($product_id > 0) {
//            $query .= " WHERE id = $product_id";
//        }

        $query .= " ORDER BY id LIMIT $start, $limit";


        if (is_null($hash_key)) {
            $products = db()->fetchAll($query, Product::class);
        } else {
            $products = db()->fetchAllHash($query, $hash_key, Product::class);
        }

        static::getFolderIdsForProducts($products);
        return $products;

    }

    public static function getCount(string $where = null) {

        $query = "SELECT COUNT(*) as count FROM products";

        if ($where) {
            $query .= $where;
        }

        /**
         * @var $result Model
         */
        $result = db()->fetchRow($query, Model::class); //создается объект класса Модел, создается его свойство count

        return (int) $result->getProperty('count') ?? 0;
    }

    /**
     * @param int $product_id
     * @return Product
     */

    public static function getById(int $product_id) {
        $query = "SELECT * FROM products WHERE id = $product_id";

        $product = db()->fetchRow($query, Product::class);

        if ($product) {
            static::getFolderIdsForProduct($product);
        }
        return $product;
    }

    public static function searchById(int $product_id) {
        $query = "SELECT * FROM products";

        $where = "  WHERE id = $product_id";

        $product = db()->fetchRow($query . $where, Product::class);

        if ($product) {
            static::getFolderIdsForProduct($product);
        }

        $products[] = $product;
        $products = [
            'count' => static::getCount($where),
            'items' => $products
        ];

        return $products;
    }

    public static function searchByName(string $product_name) {

        $query = "SELECT * FROM products";
        $where = " WHERE name LIKE '%$product_name%'";
        
        $products = db()->fetchAllHash($query . $where, 'id',Product::class);

        if ($products) {
            static::getFolderIdsForProducts($products);
        }

        $products = [
            'count' => static::getCount($where),
            'items' => $products
        ];

        return $products;
    }

    public static function searchByPrice(float $product_price_from, float $product_price_to) {

        $query = "SELECT * FROM products";
        $where = " WHERE price BETWEEN $product_price_from AND $product_price_to";

        $products = db()->fetchAllHash($query . $where, 'id',Product::class);

        if ($products) {
            static::getFolderIdsForProducts($products);
        }
        $products = [
            'count' => static::getCount($where),
            'items' => $products
        ];

        return $products;
    }

    public static function save(Product $product) {
        $data = [
          'name' => $product->getName(),
          'price'=> $product->getPrice(),
          'amount' => $product->getAmount(),
          'description'=> $product->getDescription(),
          'vendor_id' => $product->getVendorId(),
        ];

        $product_id = $product->getId();

        if ($product_id > 0) {
            db()->update('products', $data, ['id' => $product_id]);
            static::removeLinksWithFolders($product);
        } else {
            $product_id = db()->insert('products', $data);
        }

        static::updateLinksWithFolders($product_id, $product->getFolderIds());

        return static::getById($product_id);
    }

    private static function removeLinksWithFolders(Product $product) {
        db()->delete('products_folders', ['product_id' => $product->getId()]);
    }

    private static function updateLinksWithFolders(int $product_id, array $folder_ids) {

//        $folder_ids = array_unique($folder_ids);

        foreach ($folder_ids as $folder_id) {
            db()->insert('products_folders', ['product_id' => $product_id, 'folder_id' => $folder_id]);
        }
    }


    private static function getFolderIdsForProduct(Product $product) {
        $product_id = $product->getId();

        $query = "SELECT folder_id FROM products_folders WHERE product_id = $product_id";

        $folder_ids = db()->fetchAll($query, Folder::class);

        foreach ($folder_ids as $link) {
            $product->addFolderId($link->folder_id);
        }
    }

    /**
     * @param Product[] $products
     */
    private static function getFolderIdsForProducts(array $products) {

        $product_ids = array_map(function($item){
            /**
             * @var $item Product
             */
            return (int) $item->getId();
        } ,$products);

        $product_ids = array_unique($product_ids);

        if (count($product_ids) > 0) {

            $product_ids = implode(',', $product_ids);
            $query = "SELECT * FROM products_folders WHERE product_id IN ($product_ids)";
            $links = db()->fetchAll($query, Model::class);


            foreach ($links as $pair) {
                $product_id = $pair->product_id;
                $folder_id = $pair->folder_id;
                /**
                 * @todo
                 */
                foreach ($products as $product) {
                    if ($product->getId() != $product_id) {
                        continue;
                    }

                    $product->addFolderId($folder_id);

                }
            }
        }
    }
}