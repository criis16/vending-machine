<?php

namespace App\Application\Status\UpdateBalance;

use App\Domain\Status\StatusBalance;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use InvalidArgumentException;

class UpdateBalanceService
{
    private StatusRepositoryInterface $repository;

    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Updates the balance
     *
     * @param float $balance
     * @return boolean
     */
    public function execute(float $balance): bool
    {
        $currenStatus = $this->repository->getStatus();

        if (empty($currenStatus)) {
            throw new InvalidArgumentException('No status balance found in the database. Please insert coins first.');
        }

        $statusBalance = new StatusBalance($balance);
        $statusId = \reset($currenStatus)->getStatusId();
        return $this->repository->updateStatusBalance($statusId, $statusBalance);
    }
}
