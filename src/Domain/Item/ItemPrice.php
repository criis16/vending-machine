<?php

namespace App\Domain\Item;

class ItemPrice
{
    private float $price;

    public function __construct(float $price)
    {
        $this->price = $price;
    }

    /**
     * Return the coin price
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->price;
    }
}
