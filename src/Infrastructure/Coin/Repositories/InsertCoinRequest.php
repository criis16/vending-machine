<?php

namespace App\Infrastructure\Coin\Repositories;

class InsertCoinRequest
{
    private ?float $coin;

    public function __construct(
        float $coin = null
    ) {
        $this->coin = $coin;
    }

    /**
     * Get the value of coin
     *
     * @return float
     */
    public function getCoin(): float
    {
        return $this->coin;
    }

    /**
     * Set the value of coin
     *
     * @param integer $coin
     * @return void
     */
    public function setCoin(float $coin): void
    {
        $this->coin = $coin;
    }
}
