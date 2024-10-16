<?php

namespace App\Domain\Coin;

class CoinValue
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Return the coin value
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
