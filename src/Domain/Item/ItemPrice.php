<?php

namespace App\Domain\Item;

class ItemPrice
{
    private const ZERO_PRICE = 0.0;

    private float $price;

    public function __construct(float $price)
    {
        $this->price = ($price < self::ZERO_PRICE) ? self::ZERO_PRICE : $price;
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

    public function setValue(float $price): void
    {
        $this->price = ($price < self::ZERO_PRICE) ? self::ZERO_PRICE : $price;
    }
}
