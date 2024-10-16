<?php

namespace App\Domain\Coin;

class Coin
{

    private CoinId $coinId;
    private CoinValue $coinValue;
    private CoinQuantity $coinQuantity;

    public function __construct(
        CoinId $coinId,
        CoinValue $coinValue,
        CoinQuantity $coinQuantity
    ) {
        $this->coinId = $coinId;
        $this->coinValue = $coinValue;
        $this->coinQuantity = $coinQuantity;
    }

    /**
     * Get the value of coinId
     *
     * @return CoinId
     */
    public function getCoinId(): CoinId
    {
        return $this->coinId;
    }

    /**
     * Set the value of coinId
     *
     * @return void
     */
    public function setCoinId($coinId): void
    {
        $this->coinId = $coinId;
    }

    /**
     * Get the value of coinValue
     *
     * @return CoinValue
     */
    public function getCoinValue(): CoinValue
    {
        return $this->coinValue;
    }

    /**
     * Set the value of coinValue
     *
     * @return void
     */
    public function setCoinValue($coinValue): void
    {
        $this->coinValue = $coinValue;
    }

    /**
     * Get the value of coinQuantity
     *
     * @return CoinQuantity
     */
    public function getCoinQuantity(): CoinQuantity
    {
        return $this->coinQuantity;
    }

    /**
     * Set the value of coinQuantity
     *
     * @return void
     */
    public function setCoinQuantity($coinQuantity): void
    {
        $this->coinQuantity = $coinQuantity;
    }
}
