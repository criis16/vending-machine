<?php

namespace App\Application\Coin\InsertCoin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;

class InsertCoinService
{
    private const INITIAL_COIN_QUANTITY = 1;

    private CoinRepositoryInterface $repository;

    public function __construct(
        CoinRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Inserts a coin
     *
     * @param InsertCoinRequest $request
     * @return boolean
     */
    public function execute(
        InsertCoinRequest $request
    ): bool {
        $isOperationDone = false;
        $quantity = (!empty($request->getQuantity())) ? $request->getQuantity() : self::INITIAL_COIN_QUANTITY;
        $coinQuantity = new CoinQuantity($quantity);
        $coinValue = new CoinValue($request->getCoin());
        $coin = $this->repository->getCoinByValue($coinValue);

        if (empty($coin)) {
            $coin = new Coin($coinValue, $coinQuantity);
            $isOperationDone = $this->repository->saveCoin($coin);
        } else {
            $coin = \reset($coin);
            $coinId = $coin->getCoinId();
            $currentCoinQuantity = $coin->getCoinQuantity();
            $coinQuantity->setValue($currentCoinQuantity->getValue() + $coinQuantity->getValue());
            $isOperationDone = $this->repository->updateCoinQuantity($coinId, $coinQuantity);
        }

        return $isOperationDone;
    }
}
