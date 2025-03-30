<?php

namespace App\Application\Coin\GetCoinsBack;

use InvalidArgumentException;
use App\Domain\Coin\CoinValue;
use App\Domain\Status\StatusBalance;
use App\Application\Coin\Exceptions\CoinNotSavedException;
use App\Application\Coin\Exceptions\CoinsNotReturnException;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class GetCoinsBackService
{
    private const EPSILON = 0.00001;

    private GetCoinByValueService $getCoinByValueService;
    private UpdateCoinQuantityService $updateCoinQuantityService;

    public function __construct(
        GetCoinByValueService $getCoinByValueService,
        UpdateCoinQuantityService $updateCoinQuantityService
    ) {
        $this->getCoinByValueService = $getCoinByValueService;
        $this->updateCoinQuantityService = $updateCoinQuantityService;
    }

    /**
     * Returns the coins to return and its quantity
     *
     * @param float $balance
     * @return array
     */
    public function execute(float $balance): array
    {
        $coinsToReturn = $this->getCoinsQuantities($balance);

        if (!$this->validateReturnedCoins($coinsToReturn, $balance)) {
            throw new CoinsNotReturnException('The vending machine has not enough coins to return');
        }

        $this->updateCoinsQuantity($coinsToReturn);

        return $coinsToReturn;
    }

    /**
     * Returns the coins to return and its quantity based on the current balance
     *
     * @param float $balance
     * @return array
     */
    private function getCoinsQuantities(float $balance): array
    {
        $coinsToReturn = [];

        foreach (CoinValue::ALLOWED_COIN_VALUES as $returnCoinValue) {
            $coin = $this->getCoinByValueService->execute($returnCoinValue);

            if (empty($coin)) {
                continue;
            }

            $coin = \reset($coin);
            $currentCoinQuantity = $coin->getCoinQuantity()->getValue();

            $coinsAmount = \intdiv($balance * 100, $returnCoinValue * 100);
            $coinsAmount = \min($coinsAmount, $currentCoinQuantity);
            $coinsToReturn[\number_format($returnCoinValue, 2)] = $coinsAmount;

            $balance -= $coinsAmount * $returnCoinValue;
            $balance = \round($balance, 2);
        }

        return $coinsToReturn;
    }

    /**
     * Validates if the returned coins are equal to the current balance
     *
     * @param array $coinsToReturn
     * @param float $currentBalance
     * @return boolean
     */
    private function validateReturnedCoins(array $coinsToReturn, float $currentBalance): bool
    {
        $totalReturned = StatusBalance::ZERO_BALANCE;

        foreach ($coinsToReturn as $coinValue => $coinQuantity) {
            $totalReturned += $coinValue * $coinQuantity;
        }

        return \abs($totalReturned - $currentBalance) < self::EPSILON;
    }

    /**
     * Updates the coins quantity and returns the result status
     *
     * @param array $coinsToReturn
     * @return boolean
     */
    private function updateCoinsQuantity(array $coinsToReturn): void
    {
        foreach ($coinsToReturn as $coinValue => $coinQuantityToReturn) {
            if (empty($coinQuantityToReturn)) {
                continue;
            }

            $coin = $this->getCoinByValueService->execute($coinValue);

            if (empty($coin)) {
                throw new InvalidArgumentException('No coin found with the given value ' . $coinValue);
            }

            $coin = \reset($coin);
            $isUpdated = $this->updateCoinQuantityService->execute(
                $coinValue,
                $coin->getCoinQuantity()->getValue() - $coinQuantityToReturn
            );

            if (!$isUpdated) {
                throw new CoinNotSavedException('The inserted coin has not been saved');
            }
        }
    }
}
