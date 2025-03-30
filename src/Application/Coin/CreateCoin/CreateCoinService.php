<?php

namespace App\Application\Coin\CreateCoin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class CreateCoinService
{
    private CoinRepositoryInterface $repository;

    public function __construct(
        CoinRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Saves a new coin
     *
     * @param float $coinValue
     * @param integer $coinQuantity
     * @return boolean
     */
    public function execute(
        float $coinValue,
        int $coinQuantity
    ): bool {
        return $this->repository->saveCoin(
            new Coin(
                new CoinValue($coinValue),
                new CoinQuantity($coinQuantity)
            )
        );
    }
}
