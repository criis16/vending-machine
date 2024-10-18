<?php

namespace App\Application\Coin\Adapters;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;
use App\Entity\Coin as EntityCoin;

class EntityCoinAdapter
{
    /**
     * Adapts a Coin Entity object into a Domain object
     *
     * @param EntityCoin $entityCoin
     * @return Coin
     */
    public function adapt(
        EntityCoin $entityCoin
    ): Coin {
        $coin = new Coin(
            new CoinValue($entityCoin->getValue()),
            new CoinQuantity($entityCoin->getQuantity())
        );

        $coin->setCoinId(
            new CoinId($entityCoin->getId())
        );

        return $coin;
    }
}
