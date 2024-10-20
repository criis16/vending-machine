<?php

namespace App\Application\Status\UpdateBalance;

use InvalidArgumentException;
use App\Domain\Status\StatusBalance;
use App\Application\Status\GetStatus\GetStatusService;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use App\Domain\Status\StatusId;

class UpdateBalanceService
{
    private StatusRepositoryInterface $repository;
    private GetStatusService $getStatusService;

    public function __construct(
        StatusRepositoryInterface $repository,
        GetStatusService $getStatusService
    ) {
        $this->repository = $repository;
        $this->getStatusService = $getStatusService;
    }

    /**
     * Updates the balance
     *
     * @param float $balance
     * @return boolean
     */
    public function execute(float $balance): bool
    {
        $currenStatus = $this->getStatusService->execute();

        if (empty($currenStatus)) {
            throw new InvalidArgumentException('No status balance found in the database. Please insert coins first.');
        }

        $statusBalance = new StatusBalance($balance);
        $statusId = new StatusId(\reset($currenStatus)['id']);
        return $this->repository->updateStatusBalance($statusId, $statusBalance);
    }
}
