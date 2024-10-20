<?php

namespace App\Application\Status\InsertBalance;

use App\Domain\Status\Status;
use InvalidArgumentException;
use App\Domain\Status\StatusId;
use App\Domain\Status\StatusBalance;
use App\Application\Status\GetStatus\GetStatusService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Domain\Status\Repositories\StatusRepositoryInterface;

class InsertBalanceService
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
     * Inserts a balance
     *
     * @param InsertCoinRequest $request
     * @return boolean
     */
    public function execute(
        InsertCoinRequest $request
    ): bool {
        if (empty($request->getCoin())) {
            throw new InvalidArgumentException('The coin value is not valid.');
        }

        $isOperationDone = false;
        $currenStatus = $this->getStatusService->execute();
        $statusBalance = new StatusBalance($request->getCoin());

        if (empty($currenStatus)) {
            $isOperationDone = $this->createNewStatus($statusBalance->getValue());
        } else {
            $currenStatus = \reset($currenStatus);
            $currenStatus = $this->createStatus($currenStatus['balance'], $currenStatus['id']);
            $isOperationDone = $this->updateStatusBalance($currenStatus, $statusBalance);
        }

        return $isOperationDone;
    }

    /**
     * Crates a new status object
     *
     * @param float $balance
     * @param integer|null $id
     * @return Status
     */
    private function createStatus(
        float $balance,
        ?int $id = null
    ): Status {
        $statusBalance = new StatusBalance($balance);
        $status = new Status($statusBalance);

        if (!empty($id)) {
            $status->setStatusId(new StatusId($id));
        }

        return $status;
    }

    /**
     * Creates a new status in database
     *
     * @param float $balance
     * @return boolean
     */
    private function createNewStatus(float $balance): bool
    {
        return $this->repository->saveStatus(
            $this->createStatus($balance)
        );
    }

    /**
     * Updates the status balance
     *
     * @param Status $status
     * @param StatusBalance $statusBalance
     * @return boolean
     */
    private function updateStatusBalance(
        Status $status,
        StatusBalance $statusBalance
    ): bool {
        $statusId = $status->getStatusId();
        $currentBalance = $status->getStatusBalance();
        $currentBalance->setValue($currentBalance->getValue() + $statusBalance->getValue());
        return $this->repository->updateStatusBalance($statusId, $currentBalance);
    }
}
