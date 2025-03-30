<?php

namespace App\Application\Status\UpdateBalance;

use App\Application\Status\Exceptions\BalanceNotSavedException;
use InvalidArgumentException;
use App\Domain\Status\StatusId;
use App\Domain\Status\StatusBalance;
use App\Application\Status\GetStatus\GetStatusService;
use App\Domain\Status\Repositories\StatusRepositoryInterface;

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
    public function execute(
        float $balance = StatusBalance::ZERO_BALANCE
    ): void {
        $currenStatus = $this->getStatusService->execute();

        if (empty($currenStatus)) {
            throw new InvalidArgumentException('No status balance found in the database. Please insert coins first.');
        }

        $statusBalance = new StatusBalance($balance);
        $statusId = new StatusId(\reset($currenStatus)['id']);
        $isStatusUpdated = $this->repository->updateStatusBalance($statusId, $statusBalance);

        if (!$isStatusUpdated) {
            throw new BalanceNotSavedException('The inserted balance has not been updated');
        }
    }
}
