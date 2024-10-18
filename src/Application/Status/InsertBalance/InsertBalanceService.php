<?php

namespace App\Application\Status\InsertBalance;

use App\Domain\Status\Status;
use App\Domain\Status\StatusBalance;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Domain\Status\Repositories\StatusRepositoryInterface;

class InsertBalanceService
{
    private StatusRepositoryInterface $repository;

    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Inserts a balance
     *
     * @param InsertCoinRequest $request
     * @return boolean
     */
    public function execute(
        InsertCoinRequest $request
    ): bool {
        $statusBalance = new StatusBalance($request->getCoin());
        $statusResult = $this->repository->getStatus();
        $isOperationDone = false;

        if (empty($statusResult)) {
            $status = new Status($statusBalance);
            $isOperationDone = $this->repository->saveStatus($status);
        } else {
            $statusId = \reset($statusResult)->getStatusId();
            $isOperationDone = $this->repository->updateStatusBalance($statusId, $statusBalance);
        }

        return $isOperationDone;
    }
}
