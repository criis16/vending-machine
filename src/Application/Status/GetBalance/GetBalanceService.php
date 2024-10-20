<?php

namespace App\Application\Status\GetBalance;

use App\Domain\Status\Repositories\StatusRepositoryInterface;

class GetBalanceService
{
    private const EMPTY_BALANCE = 0.0;
    private StatusRepositoryInterface $repository;

    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns the current balance
     *
     * @return float
     */
    public function execute(): float
    {
        $status = $this->repository->getStatus();

        if (empty($status)) {
            return self::EMPTY_BALANCE;
        }

        $statusResult = \reset($status);
        return $statusResult->getStatusBalance()->getValue();
    }
}
