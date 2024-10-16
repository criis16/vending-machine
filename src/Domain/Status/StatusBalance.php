<?php

namespace App\Domain\Status;

class StatusBalance
{
    private const ZERO_BALANCE = 0.0;

    private float $balance;

    public function __construct(float $balance)
    {
        $this->balance = ($balance < self::ZERO_BALANCE) ? self::ZERO_BALANCE : $balance;
    }

    /**
     * Return the balance value
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->balance;
    }
}
