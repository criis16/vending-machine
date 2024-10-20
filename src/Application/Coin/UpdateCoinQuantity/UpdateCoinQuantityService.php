<?php

namespace App\Application\Coin\UpdateCoinQuantity;

use InvalidArgumentException;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;

class UpdateCoinQuantityService
{
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
     * Updates the coin quantity
     *
     * @param float $value
     * @param integer $quantity
     * @return boolean
     */
    public function execute(float $value, int $quantity): bool
    {
        $coin = $this->getCoinByValueService->execute($value);

        if (empty($coin)) {
            throw new InvalidArgumentException('No coin found with the given value' . $value);
        }

        $coin = \reset($coin);
        $coinId = $coin->getCoinId();
        $coinQuantity = new CoinQuantity($quantity);
        $coinQuantity->setValue($coin->getCoinQuantity()->getValue() - $coinQuantity->getValue());
        return $this->repository->updateCoinQuantity($coinId, $coinQuantity);
    }
}
