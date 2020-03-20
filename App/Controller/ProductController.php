<?php


namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Model\Product;
use App\Repository\FolderRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use App\Service\CartService;
use App\Service\FolderService;
use App\Service\ProductService;
use App\Service\RequestService;
use App\Service\UserService;
use App\Service\VendorService;

class ProductController extends ControllerAbstract
{

    /**
     * @param Request $request
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     *
     * @Route(url="/")
     * @Route(url="/product/list")
     *
     *
     * @return Response
     */
    public function list(Request $request, ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository) {

        $current_page = $request->getIntFromGet('page', 1);
        $per_page = 20;
        $start = $per_page * ($current_page - 1);

//        $products = ProductService::getList('id');

        $products = [
            'count' => $product_repository->getCount(),
            'items' => $product_repository->findAllWithLimit($start, $per_page)
        ];

        $vendors = $vendor_repository->findAll();
        $folders = $folder_repository->findAll();

        $paginator = [
            'pages' => ceil($products['count'] / $per_page),
            'current' => $current_page
        ];
//
//        return $this->getJsonResponse([
//            'hello' => 'world',
//        ]);

        return

        $this->render('index.tpl', [
            'products' => $products,
            'vendors' => $vendors,
            'folders' => $folders,
            'paginator' => $paginator,
        ]);
    }

    /**
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     *
     * @Route(url="/product/view/{product_id}")
     *
     * @return Response
     */
    public function view(ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository) {

//        $product_id = $this->request->getIntFromGet('product_id');
        $product_id = $this->getRoute()->getParam('product_id');

        $product = $product_repository->find($product_id);

        $folders = $folder_repository->findAll();
        $vendors = $vendor_repository->findAll();

        return $this->render('product/view.tpl', [
           'product' => $product,
           'folders' => $folders,
           'vendors' => $vendors,
        ]);
    }

    public static function search() {
        $product_id = RequestService::getIntFromGet('product_id');
        $product_name = RequestService::getStringFromGet('product_name');
        $product_price_from = RequestService::getFloatFromGet('product_price_from');
        $product_price_to = RequestService::getFloatFromGet('product_price_to');

        if ($product_id > 0) {

            $products = ProductService::searchById($product_id);

        } else if ($product_name) {
            $products = ProductService::searchByName($product_name);

        } else if ($product_price_from && $product_price_to) {

            $products = ProductService::searchByPrice($product_price_from, $product_price_to);

        }

        $folders = FolderService::getList('id');
        $vendors = VendorService::getList('id');

        smarty()->assign_by_ref('products', $products);
        smarty()->assign_by_ref('folders', $folders);
        smarty()->assign_by_ref('vendors', $vendors);
        smarty()->display('product/search.tpl');
    }

    /**
     * @param ProductRepository $product_repository
     * @param CartService $cart_service
     *
     * @Route(url="/product/buy/{product_id}")
     *
     * @return Response
     */
    public function buy(ProductRepository $product_repository, CartService $cart_service) {
//        $product_id = $this->request->getIntFromGet('product_id');
        $product_id = $this->getRoute()->getParam('product_id');
        $product = $product_repository->find($product_id);

        $cart_service->addProduct($product);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @param UserService $user_service
     * @param ProductRepository $product_repository
     * @param VendorRepository $vendor_repository
     * @param FolderRepository $folder_repository
     *
     * @Route(url="/product/edit")
     * @Route(url="/product/edit/{product_id}")
     *
     * @return Response
     */
    public function edit(UserService $user_service, ProductRepository $product_repository, VendorRepository $vendor_repository, FolderRepository $folder_repository) {
        $user = $user_service->getCurrentUser();

        if(!$user->getId()) {

            die('permission denied');
        }

//        $product_id = RequestService::getIntFromGet('product_id');
        $product_id = (int) $this->getRoute()->getParam('product_id');

        if ($product_id) {
           $product = $product_repository->find($product_id);
        } else {
            $product = $product_repository->create();
        }

//        $folders = FolderService::getList('id');
//        $vendors = VendorService::getList('id');

        $vendors = $vendor_repository->findAll();
        $folders = $folder_repository->findAll();

        $data = [
          'product' => $product,
          'folders' => $folders,
          'vendors' => $vendors,
        ];

        return $this->render('product/edit.tpl', $data);

//        smarty()->assign_by_ref('product', $product);
//        smarty()->assign_by_ref('folders', $folders);
//        smarty()->assign_by_ref('vendors', $vendors);
//        smarty()->display('product/edit.tpl');
    }

    public function editing(UserService $user_service, ProductRepository $product_repository) {

        $user = $user_service->getCurrentUser();

        if(!$user->getId()) {

            die('permission denied');
        }

        $product_id = $this->request->getIntFromPost('product_id');
        $name = $this->request->getStringFromPost('name');
        $price = $this->request->getFloatFromPost('price');
        $amount = $this->request->getIntFromPost('amount');
        $description = $this->request->getStringFromPost('description');
        $vendor_id = $this->request->getIntFromPost('vendor_id');
        $folder_ids = $this->request->getArrayFromPost('folder_ids');

        if (!$name || !$price || !$amount) {
            die('not enough data');
        }

        $product = $product_repository->findOrCreate($product_id);

//        if($product_id) {
//            $product = ProductService::getById($product_id); //?нужен только id, setId нет. mysqli_fetch_object может иниц private поля
//        }

        $product->setName($name);
        $product->setPrice($price);
        $product->setAmount($amount);
        $product->setDescription($description);
        $product->setVendorId($vendor_id);

        $product->removeAllFolders();

        foreach ($folder_ids as $folder_id) {
            $product->addFolderId($folder_id);
        }

        $product_repository->save($product);

//        RequestService::redirect('/');
        return $this->redirect('/');
    }
}