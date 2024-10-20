<?php

namespace App\Application\Coin\GetCoinByValue;

use App\Domain\Coin\CoinValue;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use InvalidArgumentException;

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

        if (!\is_numeric($coinValue)) {
            throw new InvalidArgumentException('The coin value is not valid.');
        }
        return $this->repository->getCoinByValue(
            new CoinValue($coinValue)
        );
    }
}
