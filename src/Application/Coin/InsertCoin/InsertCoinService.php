<?php

namespace App\Application\Coin\InsertCoin;

use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Domain\Coin\Coin;
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
        $quantity = (!empty($request->getQuantity())) ? $request->getQuantity() : self::INITIAL_COIN_QUANTITY;
        $coinQuantity = new CoinQuantity($quantity);

        $coin = $this->getCoinByValueService->execute($request->getCoin());

        if (empty($coin)) {
            $coinValue = new CoinValue($request->getCoin());
            $isOperationDone = $this->createNewCoin($coinValue, $coinQuantity);
        } else {
            $isOperationDone = $this->updateCoinQuantityt(\reset($coin), $coinQuantity);
        }

        return $isOperationDone;
    }

    /**
     * Creates a new coin
     *
     * @param CoinValue $coinValue
     * @param CoinQuantity $coinQuantity
     * @return boolean
     */
    private function createNewCoin(CoinValue $coinValue, CoinQuantity $coinQuantity): bool
    {
        return $this->repository->saveCoin(
            new Coin(
                $coinValue,
                $coinQuantity
            )
        );
    }

    /**
     * Updates the coin quantity
     *
     * @param Coin $coin
     * @param CoinQuantity $coinQuantity
     * @return boolean
     */
    private function updateCoinQuantityt(Coin $coin, CoinQuantity $coinQuantity): bool
    {
        $coinId = $coin->getCoinId();
        $currentCoinQuantity = $coin->getCoinQuantity();
        $coinQuantity->setValue($currentCoinQuantity->getValue() + $coinQuantity->getValue());

        return $this->repository->updateCoinQuantity($coinId, $coinQuantity);
    }
}
