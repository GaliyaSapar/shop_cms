<?php


namespace App\Service;

use App\Model\Cart;
use App\Model\Product;

class CartService
{
    /**
     * @var string
     */
    private $session_key = 'shop_cart';

    /**
     * @var Cart
     */
    private $cart;

    public function getCart() {

        if (!($this->cart instanceof Cart)) {
            if ($this->isCartExist()) {
                $cart_data = $_SESSION[$this->session_key];
                $this->cart = unserialize($cart_data);
            } else {
                $this->cart = new Cart();
            }
        }
        return $this->cart;
    }

    public function clearCart() {
        unset($_SESSION[$this->session_key]);
    }

    public function storeCart() {
        $serialized_cart = serialize($this->cart); //why using getCart()???

        $_SESSION[$this->session_key] = $serialized_cart;
    }

    public function addProduct(Product $product) {
        $cart = $this->getCart();
        $cart->add($product);
        $this->storeCart(); // here
    }

    private function isCartExist() {
        return isset($_SESSION[$this->session_key]);
    }

}