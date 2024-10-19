<?php

namespace App\Application\Coin\GetAllCoins;

use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class GetAllCoinsService
{
    private CoinRepositoryInterface $repository;

    public function __construct(
        CoinRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Returns all the coins
     *
     * @return array
     */
    public function execute(): array
    {
        $coins = $this->repository->getAllCoins();
        $coinsToReturn = [];

        foreach ($coins as $coin) {
            $coinValue = $coin->getCoinValue();
            $coinQuantity = $coin->getCoinQuantity();
            $coinsToReturn[] = [
                'value' => \number_format($coinValue->getValue(), 2),
                'quantity' => $coinQuantity->getValue()
            ];
        }

        return $coinsToReturn;
    }
}
