<?php

namespace App\Domain\Item;

class ItemQuantity
{
    private int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Return the item quantity value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->quantity;
    }
}
