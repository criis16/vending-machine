<?php

namespace App\Infrastructure\Coin;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Status\GetBalance\GetBalanceService;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Infrastructure\Shared\Exceptions\RequestException;
use App\Infrastructure\Shared\Exceptions\NotSavedException;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class GetCoinsBackController
{
    private const EMPTY_BALANCE = 0.0;
    private const EMPTY_RETURN_COINS = 'Coins returned: 0.00';
    private const EPSILON = 0.00001;
    private const ALLOWED_RETURN_COINS = [
        1.00,
        0.25,
        0.10,
        0.05
    ];

    private GetBalanceService $getBalanceService;
    private GetCoinsBackService $getCoinsBackService;
    private UpdateBalanceService $updateBalanceService;
    private UpdateCoinQuantityService $updateCoinQuantityService;

    public function __construct(
        GetBalanceService $getBalanceService,
        GetCoinsBackService $getCoinsBackService,
        UpdateBalanceService $updateBalanceService,
        UpdateCoinQuantityService $updateCoinQuantityService
    ) {
        $this->getBalanceService = $getBalanceService;
        $this->getCoinsBackService = $getCoinsBackService;
        $this->updateBalanceService = $updateBalanceService;
        $this->updateCoinQuantityService = $updateCoinQuantityService;
    }

    /**
     * Returns the inserted coins back to the user
     *
     * @return JsonResponse
     */
    public function getCoinsBack(): JsonResponse
    {
        $statusCode = 400;
        $coinsToReturn = [];

        try {
            $currentBalance = $this->getBalanceService->execute();
            if ($currentBalance === self::EMPTY_BALANCE) {
                throw new RequestException(['balance' => 'The balance is empty. Please insert coins first']);
            }

            $coinsToReturn = $this->getCoinsBackService->execute(self::ALLOWED_RETURN_COINS, $currentBalance);
            if (!$this->validateReturnedCoins($coinsToReturn, $currentBalance)) {
                throw new RequestException(['coins' => 'The vending machine has not enough coins to return']);
            }

            $this->updateCoinsQuantity($coinsToReturn);
            $isBalanceUpdated = $this->updateBalanceService->execute(self::EMPTY_BALANCE);

            if (!$isBalanceUpdated) {
                throw new NotSavedException(['balance' => 'The inserted balance has not been updated']);
            }

            $statusCode = 200;
            $responseMessage = 'The entered balance ' .
                \number_format($currentBalance, 2) .
                ' has been returned successfully.';
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        return new JsonResponse(
            [
                'message' => $responseMessage,
                'result' => $this->getResponseMessage($coinsToReturn)
            ],
            $statusCode
        );
    }

    /**
     * Updates the coins quantity and returns the result status
     *
     * @param array $coinsToReturn
     * @return boolean
     */
    private function updateCoinsQuantity(array $coinsToReturn): void
    {
        foreach ($coinsToReturn as $coinValue => $coinQuantityToReturn) {
            $isUpdated = $this->updateCoinQuantityService->execute($coinValue, $coinQuantityToReturn);

            if (!$isUpdated) {
                throw new NotSavedException(['coin' => 'The inserted coin has not been saved']);
            }
        }
    }

    /**
     * Returns the response message
     *
     * @param array $coinsToReturn
     * @return string
     */
    private function getResponseMessage(array $coinsToReturn): string
    {
        if (empty($coinsToReturn)) {
            return self::EMPTY_RETURN_COINS;
        }

        $message = '';

        foreach ($coinsToReturn as $coinValue => $coinQuantity) {
            if ($coinQuantity === 0) {
                continue;
            }

            $message .= \str_repeat($coinValue . ', ', $coinQuantity);
        }

        if (empty($message)) {
            $message = self::EMPTY_RETURN_COINS;
        } else {
            $message = 'Coins returned: ' . \rtrim($message, ', ');
        }

        return $message;
    }

    /**
     * Validates if the returned coins are equal to the current balance
     *
     * @param array $coinsToReturn
     * @param float $currentBalance
     * @return boolean
     */
    private function validateReturnedCoins(array $coinsToReturn, float $currentBalance): bool
    {
        $totalReturned = self::EMPTY_BALANCE;

        foreach ($coinsToReturn as $coinValue => $coinQuantity) {
            $totalReturned += $coinValue * $coinQuantity;
        }

        return \abs($totalReturned - $currentBalance) < self::EPSILON;
    }
}
