<?php

namespace App\Infrastructure\Coin;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Status\GetBalance\GetCurrentBalanceService;

class GetCoinsBackController
{
    private const EMPTY_COINS_MESSAGE = 'Coins returned: 0.00';

    private GetCurrentBalanceService $getCurrentBalanceService;
    private GetCoinsBackService $getCoinsBackService;
    private UpdateBalanceService $updateBalanceService;

    public function __construct(
        GetCurrentBalanceService $getCurrentBalanceService,
        GetCoinsBackService $getCoinsBackService,
        UpdateBalanceService $updateBalanceService,
    ) {
        $this->getCurrentBalanceService = $getCurrentBalanceService;
        $this->getCoinsBackService = $getCoinsBackService;
        $this->updateBalanceService = $updateBalanceService;
    }

    /**
     * Returns the inserted coins back to the user
     *
     * @return JsonResponse
     */
    public function getCoinsBack(): JsonResponse
    {
        try {
            $currentBalance = $this->getCurrentBalanceService->execute();
            $coinsToReturn = $this->getCoinsBackService->execute($currentBalance);
            $this->updateBalanceService->execute();

            $statusCode = 200;
            $responseMessage = 'The entered balance ' .
                \number_format($currentBalance, 2) .
                ' has been returned successfully.';
        } catch (Exception $e) {
            $statusCode = 400;
            $coinsToReturn = [];
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
     * Returns the response message
     *
     * @param array $coinsToReturn
     * @return string
     */
    private function getResponseMessage(array $coinsToReturn): string
    {
        if (empty($coinsToReturn)) {
            return self::EMPTY_COINS_MESSAGE;
        }

        $message = '';

        foreach ($coinsToReturn as $coinValue => $coinQuantity) {
            if (empty($coinQuantity)) {
                continue;
            }

            $message .= \str_repeat($coinValue . ', ', $coinQuantity);
        }

        return empty($message) ? self::EMPTY_COINS_MESSAGE : 'Coins returned: ' . \rtrim($message, ', ');
    }
}
