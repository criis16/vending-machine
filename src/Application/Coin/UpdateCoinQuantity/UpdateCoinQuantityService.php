<?php

namespace App\Application\Coin\UpdateCoinQuantity;

use InvalidArgumentException;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class UpdateCoinQuantityService
{
    private CoinRepositoryInterface $repository;

    public function __construct(CoinRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
        $coinValue = new CoinValue($value);
        $coinQuantity = new CoinQuantity($quantity);
        $coin = $this->repository->getCoinByValue($coinValue);

        if (empty($coin)) {
            throw new InvalidArgumentException('No coin found with the given value' . $value);
        }

        $coin = \reset($coin);
        $coinId = $coin->getCoinId();
        $coinQuantity->setValue($coin->getCoinQuantity()->getValue() - $coinQuantity->getValue());
        return $this->repository->updateCoinQuantity($coinId, $coinQuantity);
    }
}
