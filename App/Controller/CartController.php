<?php


namespace App\Controller;


use App\Service\CartService;
use App\Service\RequestService;

class CartController extends ControllerAbstract
{

    /**
     * @param CartService $cart_service
     * @return \App\Http\Response
     *
     * @Route(url="/cart/clear")
     */
    public function clear(CartService $cart_service) {
        $cart_service->clearCart();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

//    public static function view() {
//        smarty()->display('cart/view.tpl');
//    }

}