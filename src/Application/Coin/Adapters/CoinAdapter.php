<?php

namespace App\Application\Coin\Adapters;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;
use App\Entity\Coin as EntityCoin;

class CoinAdapter
{
    /**
     * Adapts a Coin Domain object to an array
     *
     * @param Coin $coin
     * @return array
     */
    public function adapt(
        Coin $coin
    ): array {
        return [
            'value' => \number_format($coin->getCoinValue()->getValue(), 2),
            'quantity' => $coin->getCoinQuantity()->getValue()
        ];
    }
}
