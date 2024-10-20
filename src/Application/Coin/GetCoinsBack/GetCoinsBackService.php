<?php

namespace App\Application\Coin\GetCoinsBack;

use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use InvalidArgumentException;

class GetCoinsBackService
{
    private GetCoinByValueService $getCoinByValueService;

    public function __construct(
        GetCoinByValueService $getCoinByValueService
    ) {
        $this->getCoinByValueService = $getCoinByValueService;
    }

    /**
     * Returns the coins to return and its quantity
     *
     * @param array $allowedReturnCoins
     * @param float $balance
     * @return array
     */
    public function execute(array $allowedReturnCoins, float $balance): array
    {

        if (empty($allowedReturnCoins)) {
            throw new InvalidArgumentException('The allowed return coins empty.');
        }

        $coinsToReturn = [];

        foreach ($allowedReturnCoins as $returnCoinValue) {
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
}
