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
     * Get the domain object of coinId
     *
     * @return CoinId
     */
    public function getCoinId(): CoinId
    {
        return $this->coinId;
    }

    /**
     * Get the domain object of coinValue
     *
     * @return CoinValue
     */
    public function getCoinValue(): CoinValue
    {
        return $this->coinValue;
    }

    /**
     * Get the domain object of coinQuantity
     *
     * @return CoinQuantity
     */
    public function getCoinQuantity(): CoinQuantity
    {
        return $this->coinQuantity;
    }
}
