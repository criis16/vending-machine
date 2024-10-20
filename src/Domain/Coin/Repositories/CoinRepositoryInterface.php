<?php

namespace App\Domain\Coin\Repositories;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;

interface CoinRepositoryInterface
{
    /**
     * Returns all the coins
     *
     * @return array
     */
    public function getAllCoins(): array;

    /**
     * Returns the coins by value
     *
     * @param CoinValue $coinValue
     * @return array
     */
    public function getCoinByValue(CoinValue $coinValue): array;

    /**
     * Save the given coin
     *
     * @param Coin $coin
     * @return boolean
     */
    public function saveCoin(Coin $coin): bool;

    /**
     * Update the given coin quantity
     *
     * @param CoinId $coinId
     * @param CoinQuantity $coinQuantity
     * @return boolean
     */
    public function updateCoinQuantity(
        CoinId $coinId,
        CoinQuantity $coinQuantity
    ): bool;
}
