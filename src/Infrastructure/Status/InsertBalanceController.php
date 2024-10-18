<?php

namespace App\Infrastructure\Status;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Application\Status\GetBalance\GetBalanceService;
use App\Infrastructure\Shared\ValidateRequestDataService;
use App\Application\Status\InsertBalance\InsertBalanceService;
use App\Infrastructure\Status\Repositories\InsertBalanceRequest;

class InsertBalanceController
{
    private const REQUIRED_BODY_FIELDS = [
        'coin'
    ];

    private InsertBalanceService $insertBalanceService;
    private GetBalanceService $getBalanceService;
    private InsertBalanceRequest $request;
    private ValidateRequestDataService $validator;
    private InsertCoinService $insertCoinService;

    public function __construct(
        InsertBalanceService $insertBalanceService,
        GetBalanceService $getBalanceService,
        InsertBalanceRequest $request,
        ValidateRequestDataService $validator,
        InsertCoinService $insertCoinService
    ) {
        $this->insertBalanceService = $insertBalanceService;
        $this->getBalanceService = $getBalanceService;
        $this->request = $request;
        $this->validator = $validator;
        $this->insertCoinService = $insertCoinService;
    }

    /**
     * Method to insert a new balance coin
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function insertBalance(Request $request): JsonResponse
    {
        $isCoinSaved = false;
        $isBalanceSaved = false;
        $statusCode = 400;

        try {
            $requestBody = $request->request->all();
            $this->validator->validate($requestBody, self::REQUIRED_BODY_FIELDS);
            $this->setRequestData($requestBody);

            $isCoinSaved = $this->insertCoinService->execute($this->request);
            $isBalanceSaved = $this->insertBalanceService->execute($this->request);
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        if ($isCoinSaved && $isBalanceSaved) {
            $statusCode = 200;
            $responseMessage = 'Coin has been inserted correctly';
        }

        $currentBalance = $this->getBalanceService->execute();

        return new JsonResponse(
            [
                'message' => $responseMessage,
                'result' => 'The current balance is ' . \number_format($currentBalance, 2)
            ],
            $statusCode
        );
    }

    /**
     * Set request data
     *
     * @param array $requestBody
     * @return void
     */
    private function setRequestData(array $requestBody): void
    {
        $this->request->setCoin($requestBody['coin']);
    }
}
