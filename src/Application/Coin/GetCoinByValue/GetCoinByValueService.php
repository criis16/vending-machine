<?php

namespace App\Application\Coin\GetCoinByValue;

use App\Domain\Coin\CoinValue;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class GetCoinByValueService
{
    private CoinRepositoryInterface $repository;

    public function __construct(
        CoinRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Returns the coin by value
     *
     * @param float $coinValue
     * @return array
     */
    public function execute(float $coinValue): array
    {
        return $this->repository->getCoinByValue(
            new CoinValue($coinValue)
        );
    }
}
