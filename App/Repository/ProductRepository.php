<?php


namespace App\Repository;


use App\Model\Model;
use App\Model\Product;

class ProductRepository extends RepositoryAbstract
{
    protected $model = Product::class;

    protected function modifyResultList(array $result)
    {
        $result = parent::modifyResultList($result);
        $this->getFolderIdsForProducts($result);

        return $result;
    }

    private function getFolderIdsForProducts(array $products) {

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
            $links = $this->mySQL->fetchAll($query, Model::class);


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