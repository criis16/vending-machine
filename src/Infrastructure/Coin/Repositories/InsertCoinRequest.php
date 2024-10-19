<?php

namespace App\Infrastructure\Coin\Repositories;

class InsertCoinRequest
{
    private ?float $coin;
    private ?int $quantity;

    public function __construct(
        float $coin = null,
        int $quantity = null
    ) {
        $this->coin = $coin;
        $this->quantity = $quantity;
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

    /**
     * Get the value of quantity
     *
     * @return integer
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @param integer $quantity
     * @return void
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
