<?php


namespace App\Model;


class CartItem
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var int
     */
    private $amount;

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function incrementAmount(int $amount = 1): void
    {
        $this->amount += $amount;
    }

    public function decrementAmount(int $amount): void
    {
        $this->amount--;
    }





}