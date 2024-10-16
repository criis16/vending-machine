<?php

namespace App\Domain\Coin;

class CoinId
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Return the coin id value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->id;
    }
}
