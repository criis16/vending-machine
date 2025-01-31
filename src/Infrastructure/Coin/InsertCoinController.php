<?php

namespace App\Infrastructure\Coin;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Application\Status\GetBalance\GetBalanceService;
use App\Infrastructure\Shared\ValidateRequestDataService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Status\InsertBalance\InsertBalanceService;
use App\Infrastructure\Shared\Exceptions\NotSavedException;
use App\Infrastructure\Shared\Exceptions\RequestException;

class InsertCoinController
{
    private const REQUIRED_BODY_FIELDS = [
        'coin'
    ];

    private InsertBalanceService $insertBalanceService;
    private GetBalanceService $getBalanceService;
    private InsertCoinRequest $request;
    private ValidateRequestDataService $validator;
    private InsertCoinService $insertCoinService;

    public function __construct(
        InsertBalanceService $insertBalanceService,
        GetBalanceService $getBalanceService,
        InsertCoinRequest $request,
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
    public function insertCoin(Request $request): JsonResponse
    {
        $isCoinSaved = false;
        $isBalanceSaved = false;
        $statusCode = 400;

        try {
            $requestBody = $request->request->all();
            $this->validator->validate($requestBody, self::REQUIRED_BODY_FIELDS);
            $this->setRequestData($requestBody);

            $isCoinSaved = $this->insertCoinService->execute($this->request);
            if (!$isCoinSaved) {
                throw new NotSavedException(['coin' => 'The inserted coin has not been saved']);
            }

            $isBalanceSaved = $this->insertBalanceService->execute($this->request);
            if (!$isBalanceSaved) {
                throw new NotSavedException(['balance' => 'The inserted balance has not been saved']);
            }

            $statusCode = 200;
            $responseMessage = 'Coin has been inserted correctly';
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
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
        if (empty($requestBody['coin'])) {
            throw new RequestException(['coin' => 'Coin must be a valid value']);
        }

        $this->request->setCoin($requestBody['coin']);
    }
}
