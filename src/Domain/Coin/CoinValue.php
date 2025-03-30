<?php

namespace App\Domain\Coin;

use InvalidArgumentException;

class CoinValue
{
    private const FIVE_CENTS_COIN_VALUE = 0.05;
    private const TEN_CENTS_COIN_VALUE = 0.10;
    private const TWENTY_FIVE_CENTS_COIN_VALUE = 0.25;
    private const ONE_EURO_COIN_VALUE = 1.00;

    public const ALLOWED_COIN_VALUES = [
        self::FIVE_CENTS_COIN_VALUE,
        self::TEN_CENTS_COIN_VALUE,
        self::TWENTY_FIVE_CENTS_COIN_VALUE,
        self::ONE_EURO_COIN_VALUE,
    ];

    public const ALLOWED_RETURN_COIN_VALUES = [
        self::FIVE_CENTS_COIN_VALUE,
        self::TEN_CENTS_COIN_VALUE,
        self::TWENTY_FIVE_CENTS_COIN_VALUE
    ];

    private float $value;

    public function __construct(float $value)
    {
        $this->validate($value);
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

    private function validate(float $value): void
    {
        if (!\in_array($value, self::ALLOWED_COIN_VALUES)) {
            throw new InvalidArgumentException(
                'Invalid coin value ' . $value . '. Allowed values are: ' . implode(', ', self::ALLOWED_COIN_VALUES)
            );
        }
    }
}
