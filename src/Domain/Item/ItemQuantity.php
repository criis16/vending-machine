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

    /**
     * Sets the item quantity value
     *
     * @param integer $quantity
     * @return void
     */
    public function setValue(int $quantity): void
    {
        $this->quantity = ($quantity < self::ZERO_QUANTITY) ? self::ZERO_QUANTITY : $quantity;
    }
}
