<?php

namespace App\Application\Coin\InsertCoin;

use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;

class InsertCoinService
{
    private const INITIAL_COIN_QUANTITY = 1;

    private CoinRepositoryInterface $repository;
    private GetCoinByValueService $getCoinByValueService;

    public function __construct(
        CoinRepositoryInterface $repository,
        GetCoinByValueService $getCoinByValueService
    ) {
        $this->repository = $repository;
        $this->getCoinByValueService = $getCoinByValueService;
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
        $coinValue = $request->getCoin();
        $quantity = $request->getQuantity();
        $quantity = (!empty($quantity)) ? $quantity : self::INITIAL_COIN_QUANTITY;

        $coin = $this->getCoinByValueService->execute($coinValue);

        if (empty($coin)) {
            $isOperationDone = $this->createNewCoin($coinValue, $quantity);
        } else {
            $coin = \reset($coin);
            $isOperationDone = $this->updateCoinQuantityt(
                $coin->getCoinId()->getValue(),
                $coin->getCoinQuantity()->getValue(),
                $quantity
            );
        }

        return $isOperationDone;
    }

    /**
     * Creates a new coin
     *
     * @param float $coinValue
     * @param integer $coinQuantity
     * @return boolean
     */
    private function createNewCoin(float $coinValue, int $coinQuantity): bool
    {
        return $this->repository->saveCoin(
            new Coin(
                new CoinValue($coinValue),
                new CoinQuantity($coinQuantity)
            )
        );
    }

    /**
     * Updates the coin quantity
     *
     * @param float $coinId
     * @param integer $coinQuantity
     * @param integer $newCoinQuantity
     * @return boolean
     */
    private function updateCoinQuantityt(
        float $coinId,
        int $coinQuantity,
        int $newCoinQuantity
    ): bool {
        $coinId = new CoinId($coinId);
        $coinQuantity = new CoinQuantity($coinQuantity + $newCoinQuantity);
        return $this->repository->updateCoinQuantity($coinId, $coinQuantity);
    }
}
