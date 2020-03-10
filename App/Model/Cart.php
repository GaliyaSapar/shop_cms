<?php


namespace App\Model;


class Cart
{
    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @var float
     */
    protected $price = 0;

    /**
     * @var CartItem[]
     */
    protected $cart_items = [];


    public function add(Product $product) {


//        $cartItem = new CartItem($product);
//        $cartItem->setAmount(1);

        $cart_item = $this->getCartItem($product);
        $cart_item->incrementAmount();

        $this->amount += 1; //
        $this->price += $product->getPrice(); //

        $this->addCartItem($cart_item);
    }

    private function addCartItem(CartItem $cart_item) {
//        $this->cart_items[] = $cart_item;
        $product_id = $cart_item->getProduct()->getId();
        $this->cart_items[$product_id] = $cart_item;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return CartItem[]
     */
    public function getCartItems(): array
    {
        return $this->cart_items;
    }

    public function getCartItemsCount() {
        return count($this->getCartItems());
    }

    private function getCartItem(Product $product) {
        $product_id = $product->getId();
        return $this->cart_items[$product_id] ?? new CartItem($product);
    }





}