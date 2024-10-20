<?php

namespace App\Application\Status\GetBalance;

use App\Application\Status\GetStatus\GetStatusService;

class GetBalanceService
{
    private const EMPTY_BALANCE = 0.0;
    private GetStatusService $getStatusService;

    public function __construct(
        GetStatusService $getStatusService
    ) {
        $this->getStatusService = $getStatusService;
    }

    /**
     * Returns the current balance
     *
     * @return float
     */
    public function execute(): float
    {
        $status = $this->getStatusService->execute();

        if (empty($status)) {
            return self::EMPTY_BALANCE;
        }

        $statusResult = \reset($status);
        return $statusResult['balance'];
    }
}
