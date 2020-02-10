<?php


namespace App\Model;


class Cart
{
    /**
     * @var int
     */
    private $amount = 0;

    /**
     * @var float
     */
    private $price = 0;

    /**
     * @var CartItem[]
     */
    private $cart_items = [];


    public function add(Product $product) {
//        $this->amount += $product->getAmount();
//        $this->price += $product->getPrice();

        $cartItem = new CartItem($product);
        $cartItem->setAmount(1);

        $this->addCartItem($cartItem);
    }

    private function addCartItem(CartItem $cartItem) {
        $this->cart_items[] = $cartItem;
    }

}