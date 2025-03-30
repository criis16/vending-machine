<?php

namespace App\Domain\Item;

class ItemPrice
{
    private const ZERO_PRICE = 0.0;
    public const WATER_PRICE = 0.65;
    public const JUICE_PRICE = 1.00;
    public const SODA_PRICE = 1.50;

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
