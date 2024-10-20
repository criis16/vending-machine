<?php

namespace App\Application\Coin\GetAllCoins;

use App\Application\Coin\Adapters\CoinAdapter;
use App\Domain\Coin\Coin;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class GetAllCoinsService
{
    private CoinRepositoryInterface $repository;
    private CoinAdapter $adapter;

    public function __construct(
        CoinRepositoryInterface $repository,
        CoinAdapter $adapter
    ) {
        $this->repository = $repository;
        $this->adapter = $adapter;
    }

    /**
     * Returns all the coins
     *
     * @return array
     */
    public function execute(): array
    {
        return \array_map(
            function (Coin $coin) {
                return $this->adapter->adapt($coin);
            },
            $this->repository->getAllCoins()
        );
    }
}
