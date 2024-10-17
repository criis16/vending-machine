<?php

namespace App\Infrastructure\Status;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function __construct(
        InsertBalanceService $insertBalanceService,
        GetBalanceService $getBalanceService,
        InsertBalanceRequest $request,
        ValidateRequestDataService $validator
    ) {
        $this->insertBalanceService = $insertBalanceService;
        $this->getBalanceService = $getBalanceService;
        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * Method to insert a new balance coin
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function insertBalance(Request $request): JsonResponse
    {
        $isSaved = false;
        $statusCode = 400;
        $balance = 0.0;

        try {
            $requestBody = $request->request->all();
            $this->validator->validate($requestBody, self::REQUIRED_BODY_FIELDS);
            $this->setRequestData($requestBody);
            $isSaved = $this->insertBalanceService->execute($this->request);
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        if ($isSaved) {
            $statusCode = 200;
            $responseMessage = 'Coin has been inserted correctly';
            $balance = $this->getBalanceService->execute();
        }

        return new JsonResponse(
            [
                'message' => $responseMessage,
                'result' => 'The current balance is ' . \number_format($balance, 2)
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
