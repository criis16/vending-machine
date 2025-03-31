<?php

namespace App\Application\Status\InsertBalance;

use App\Application\Status\GetStatus\GetStatusService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Status\CreateStatus\CreateStatusService;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Status\Exceptions\BalanceNotSavedException;

class InsertBalanceService
{
    private const BALANCE_FIELD = 'balance';

    private GetStatusService $getStatusService;
    private CreateStatusService $createStatusService;
    private UpdateBalanceService $updateBalanceService;

    public function __construct(
        GetStatusService $getStatusService,
        CreateStatusService $createStatusService,
        UpdateBalanceService $updateBalanceService
    ) {
        $this->getStatusService = $getStatusService;
        $this->createStatusService = $createStatusService;
        $this->updateBalanceService = $updateBalanceService;
    }

    /**
     * Inserts a balance
     *
     * @param InsertCoinRequest $request
     * @return boolean
     */
    public function execute(
        InsertCoinRequest $request
    ): void {
        $isOperationDone = false;
        $coinValue = $request->getCoin();
        $currenStatus = $this->getStatusService->execute();

        if (empty($currenStatus)) {
            $isOperationDone = $this->createStatusService->execute($coinValue);
        } else {
            $currentStatus = \reset($currenStatus);
            $currentStatusBalance = $currentStatus[self::BALANCE_FIELD];
            $this->updateBalanceService->execute($currentStatusBalance + $coinValue);
            $isOperationDone = true;
        }

        if (!$isOperationDone) {
            throw new BalanceNotSavedException('The given balance has not been saved');
        }
    }
}
