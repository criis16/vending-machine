<?php

namespace App\Domain\Item;

class ItemQuantity
{
    private const ZERO_QUANTITY = 0;

    private int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = ($quantity < self::ZERO_QUANTITY) ? self::ZERO_QUANTITY : $quantity;
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
