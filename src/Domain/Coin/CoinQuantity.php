<?php

namespace App\Domain\Coin;

class CoinQuantity
{
    private int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
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
}
