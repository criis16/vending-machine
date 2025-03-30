<?php

namespace App\Application\Coin\InsertCoin;

use InvalidArgumentException;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;

class InsertCoinsService
{
    private InsertCoinRequest $insertCoinRequest;
    private InsertCoinService $insertCoinService;

    public function __construct(
        InsertCoinRequest $insertCoinRequest,
        InsertCoinService $insertCoinService,
    ) {
        $this->insertCoinRequest = $insertCoinRequest;
        $this->insertCoinService = $insertCoinService;
    }

    /**
     * Inserts the given coins into the machine.
     *
     * @param array $coins
     * @throws InvalidArgumentException
     * @return void
     */
    public function execute(
        array $coins
    ): void {
        foreach ($coins as $coinValue => $coinQuantity) {
            if (empty($coinValue) || empty($coinQuantity)) {
                throw new InvalidArgumentException(
                    'Something is wrong with the coins. Value:' . $coinValue . ' Quantity:' . $coinQuantity
                );
            }

            $this->insertCoinRequest->setCoin($coinValue);
            $this->insertCoinRequest->setQuantity($coinQuantity);
            $this->insertCoinService->execute($this->insertCoinRequest);
        }
    }
}
