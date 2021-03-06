<?php


namespace App\Repository;


use App\Model\AbstractEntity;
use App\Model\Model;
use App\Model\ModelAbstract;
use App\Model\Product;

/**
 * Class ProductRepository
 * @package App\Repository
 *
 * @method Product find(int $id)
 */

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
            $links = $this->odm->fetchAll($query, Model::class);


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

    public function save(AbstractEntity $product): AbstractEntity {
//        $data = [
//          'name' => $product->getName(),
//          'price' => $product->getPrice(),
//          'amount' => $product->getAmount(),
//          'description'=>$product->getDescription(),
//          'vendor_id'=>$product->getVendorId(),
//        ];
        /**
         * @var $product Product
         */
        $entity = parent::save($product);

        $product_id = $product->getId();
//        if ($product_id > 0) {
//            $this->odm->update('products', $data, [
//                'id' => $product_id
//            ]);
//            $this->removeLinksWithFolders($product);
//        } else {
//            $product_id = $this->odm->insert('products', $data);
//        }
        if (!$product_id) {
            $product_id = $entity->getPrimaryKeyValue();
        }

        $this->removeLinksWithFolders($product_id);
        $this->updateLinksWithFolders($product_id, $product->getFolderIds());

        return $this->find($product_id);
    }

    private function removeLinksWithFolders(int $product_id) {
        $this->odm->getArrayDataManager()->delete('products_folders', [
            'product_id' => $product_id
        ]);
    }

    private function updateLinksWithFolders(int $product_id, array $folder_ids) {

        $folder_ids = array_unique($folder_ids);

        foreach ($folder_ids as $folder_id) {
            $this->odm->getArrayDataManager()->insert('products_folders', ['product_id' => $product_id, 'folder_id' => $folder_id]);
        }
    }

}