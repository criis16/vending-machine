<?php

namespace App\Application\Status\GetBalance;

use App\Application\Status\Exceptions\EmptyBalanceException;

class GetCurrentBalanceService
{
    private GetBalanceService $getBalanceService;

    public function __construct(
        GetBalanceService $getBalanceService
    ) {
        $this->getBalanceService = $getBalanceService;
    }

    /**
     * Returns the current balance
     *
     * @throws EmptyBalanceException
     * @return float
     */
    public function execute(): float
    {
        $balance = $this->getBalanceService->execute();

        if (empty($balance)) {
            throw new EmptyBalanceException('The current balance is empty. Please insert coins first.');
        }

        return $balance;
    }
}
