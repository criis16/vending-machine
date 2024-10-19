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
        $currenStatus = $this->repository->getStatus();
        $isOperationDone = false;

        if (empty($currenStatus)) {
            $status = new Status($statusBalance);
            $isOperationDone = $this->repository->saveStatus($status);
        } else {
            $currenStatus = \reset($currenStatus);
            $statusId = $currenStatus->getStatusId();
            $currentBalance = $currenStatus->getStatusBalance();
            $currentBalance->setValue($currentBalance->getValue() + $statusBalance->getValue());
            $isOperationDone = $this->repository->updateStatusBalance($statusId, $currentBalance);
        }

        return $isOperationDone;
    }
}
