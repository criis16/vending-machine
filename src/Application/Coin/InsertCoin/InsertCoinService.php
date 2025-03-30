<?php

namespace App\Application\Coin\InsertCoin;

use App\Application\Coin\CreateCoin\CreateCoinService;
use App\Application\Coin\Exceptions\CoinNotSavedException;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class InsertCoinService
{
    private const INITIAL_COIN_QUANTITY = 1;

    private GetCoinByValueService $getCoinByValueService;
    private CreateCoinService $createCoinService;
    private UpdateCoinQuantityService $updateCoinQuantityService;

    public function __construct(
        GetCoinByValueService $getCoinByValueService,
        CreateCoinService $createCoinService,
        UpdateCoinQuantityService $updateCoinQuantityService
    ) {
        $this->getCoinByValueService = $getCoinByValueService;
        $this->createCoinService = $createCoinService;
        $this->updateCoinQuantityService = $updateCoinQuantityService;
    }

    /**
     * Inserts a coin
     *
     * @param InsertCoinRequest $request
     * @throws CoinNotSavedException
     * @return void
     */
    public function execute(
        InsertCoinRequest $request
    ): void {
        $isOperationDone = false;
        $coinValue = $request->getCoin();
        $quantity = $request->getQuantity();
        $quantity = (!empty($quantity)) ? $quantity : self::INITIAL_COIN_QUANTITY;

        $coin = $this->getCoinByValueService->execute($coinValue);

        if (empty($coin)) {
            $isOperationDone = $this->createCoinService->execute($coinValue, $quantity);
        } else {
            $isOperationDone = $this->updateCoinQuantityService->execute($coinValue, $quantity);
        }

        if (!$isOperationDone) {
            throw new CoinNotSavedException('The inserted coin has not been saved');
        }
    }
}
