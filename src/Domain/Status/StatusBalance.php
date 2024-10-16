<?php

namespace App\Domain\Status;

class StatusBalance
{
    private float $balance;

    public function __construct(float $balance)
    {
        $this->balance = $balance;
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
