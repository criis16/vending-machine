<?php

namespace App\Infrastructure\Coin;

use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Application\Status\GetBalance\GetBalanceService;
use App\Infrastructure\Shared\ValidateRequestDataService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Status\InsertBalance\InsertBalanceService;

class InsertCoinController
{
    private const COIN_FIELD = 'coin';
    private const REQUIRED_BODY_FIELDS = [
        self::COIN_FIELD
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
        try {
            $requestBody = $request->request->all();
            $this->validator->validate($requestBody, self::REQUIRED_BODY_FIELDS);
            $this->setRequestData($requestBody);

            $this->insertCoinService->execute($this->request);
            $this->insertBalanceService->execute($this->request);

            $statusCode = 200;
            $responseMessage = 'Coin has been inserted correctly';
        } catch (Exception $e) {
            $statusCode = 400;
            $responseMessage = $e->getMessage();
        }

        return new JsonResponse(
            [
                'message' => $responseMessage,
                'result' => 'The current balance is ' . \number_format($this->getBalanceService->execute(), 2)
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
        if (!\is_numeric($requestBody[self::COIN_FIELD])) {
            throw new InvalidArgumentException('Coin must be a number');
        }

        $this->request->setCoin($requestBody[self::COIN_FIELD]);
    }
}
