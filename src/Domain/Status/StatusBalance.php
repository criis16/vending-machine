<?php

namespace App\Domain\Status;

class StatusBalance
{
    public const ZERO_BALANCE = 0.0;

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

    /**
     * Set the balance value
     *
     * @param float $balance
     * @return void
     */
    public function setValue(float $balance): void
    {
        $this->balance = ($balance < self::ZERO_BALANCE) ? self::ZERO_BALANCE : $balance;
    }
}
