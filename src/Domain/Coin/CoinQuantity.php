<?php

namespace App\Domain\Coin;

class CoinQuantity
{
    private const ZERO_QUANTITY = 0;

    private int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = ($quantity < self::ZERO_QUANTITY) ? self::ZERO_QUANTITY : $quantity;
    }

    /**
     * Return the coin quantity value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->quantity;
    }

    /**
     * Set the coin quantity value
     *
     * @param integer $quantity
     * @return void
     */
    public function setValue(int $quantity): void
    {
        $this->quantity = ($quantity < self::ZERO_QUANTITY) ? self::ZERO_QUANTITY : $quantity;
    }
}
