<?php

namespace App\Infrastructure\Item;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Status\GetBalance\GetBalanceService;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Infrastructure\Shared\Exceptions\RequestException;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Infrastructure\Shared\Exceptions\NotSavedException;
use App\Application\Item\GetItemByName\GetItemByNameService;
use App\Application\Item\UpdateQuantity\UpdateQuantityService;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Infrastructure\Shared\ValidateRequestQueryDataService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class GetItemByNameController
{
    private const ITEM_QUANTITY_DECREASE = 1;
    private const EMPTY_BALANCE = 0.0;
    private const EMPTY_RETURN_COINS = 'Coins returned: 0.00';
    private const REQUIRED_GET_FIELDS = [
        'item_name'
    ];
    private const ALLOWED_RETURN_COINS = [
        0.25,
        0.10,
        0.05
    ];

    private ValidateRequestQueryDataService $validator;
    private InsertItemRequest $insertItemRequest;
    private GetItemByNameService $getItemByNameService;
    private GetBalanceService $getBalanceService;
    private UpdateQuantityService $updateQuantityService;
    private UpdateBalanceService $updateBalanceService;
    private GetCoinsBackService $getCoinsBackService;
    private UpdateCoinQuantityService $updateCoinQuantityService;

    public function __construct(
        ValidateRequestQueryDataService $validator,
        InsertItemRequest $insertItemRequest,
        GetItemByNameService $getItemByNameService,
        GetBalanceService $getBalanceService,
        UpdateQuantityService $updateQuantityService,
        UpdateBalanceService $updateBalanceService,
        GetCoinsBackService $getCoinsBackService,
        UpdateCoinQuantityService $updateCoinQuantityService
    ) {
        $this->validator = $validator;
        $this->insertItemRequest = $insertItemRequest;
        $this->getItemByNameService = $getItemByNameService;
        $this->getBalanceService = $getBalanceService;
        $this->updateQuantityService = $updateQuantityService;
        $this->updateBalanceService = $updateBalanceService;
        $this->getCoinsBackService = $getCoinsBackService;
        $this->updateCoinQuantityService = $updateCoinQuantityService;
    }

    /**
     * Returns the item with the given name
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getItem(Request $request): JsonResponse
    {
        $statusCode = 400;
        $coinsToReturn = [];
        $result = '';

        try {
            $requestQueryData = $request->query->all();
            $this->validator->validate($requestQueryData, self::REQUIRED_GET_FIELDS);
            $this->setRequestData($requestQueryData);

            $item = $this->getItemValue($this->insertItemRequest);

            if (empty($item)) {
                throw new RequestException(['item_name' => 'The item does not exist. Please contact the service team.']);
            }

            $item = \reset($item);

            $itemName = $item['name'];
            $itemQuantity = $item['quantity'];
            $itemPrice = $item['price'];

            $this->validateItemQuantity($itemQuantity);

            $currentBalance = $this->getBalanceService->execute();
            $this->validateBalance($currentBalance, $itemPrice);
            $currentBalance -= $itemPrice;

            $this->updateQuantityService->execute(
                $this->insertItemRequest,
                $itemQuantity - self::ITEM_QUANTITY_DECREASE
            );
            $this->updateBalanceService->execute($currentBalance);

            $coinsToReturn = $this->getCoinsBackService->execute(self::ALLOWED_RETURN_COINS, $currentBalance);
            $this->updateBalanceService->execute(self::EMPTY_BALANCE);
            $this->updateCoinsQuantity($coinsToReturn);

            $responseMessage = 'The item has been selected successfully.';
            $result = $this->getResponseMessage($itemName, $coinsToReturn);
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        return new JsonResponse(
            [
                'message' => $responseMessage,
                'result' => $result
            ],
            $statusCode
        );
    }

    /**
     * Set request data
     *
     * @param array $requestQuery
     * @return void
     */
    private function setRequestData(array $requestQuery): void
    {
        if (empty($requestQuery['item_name'])) {
            throw new RequestException(['item_name' => 'Item name must be a valid value']);
        }

        $this->insertItemRequest->setName(\ucfirst($requestQuery['item_name']));
    }

    /**
     * Returns the item value
     *
     * @param InsertItemRequest $requestQuery
     * @return array
     */
    private function getItemValue(InsertItemRequest $requestQuery): array
    {
        return $this->getItemByNameService->execute($requestQuery);
    }

    /**
     * Checks if the item quantity is greater than 0
     *
     * @param integer $itemQuantity
     * @return void
     */
    private function validateItemQuantity(int $itemQuantity): void
    {
        if ($itemQuantity === 0) {
            throw new RequestException(
                ['item_name' => 'The item is not available. Please contact the service.']
            );
        }
    }

    /**
     * Checks if the balance is greater than the item price
     *
     * @param float $currentBalance
     * @param float $itemPrice
     * @return void
     */
    private function validateBalance(float $currentBalance, float $itemPrice): void
    {
        if ($currentBalance < $itemPrice) {
            throw new RequestException(
                ['current_balance' => 'The balance is not enough. Please insert coins.']
            );
        }
    }

    /**
     * Returns the response message
     *
     * @param string $selectedItemName
     * @param array $coinsToReturn
     * @return string
     */
    private function getResponseMessage(string $selectedItemName, array $coinsToReturn): string
    {
        return 'Selected item: ' . $selectedItemName . ', ' . $this->getCoinsMessage($coinsToReturn);
    }

    /**
     * Returns the response message
     *
     * @param array $coinsToReturn
     * @return string
     */
    private function getCoinsMessage(array $coinsToReturn): string
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
     * Updates the coins quantity and returns the result status
     *
     * @param array $coinsToReturn
     * @return void
     */
    private function updateCoinsQuantity(array $coinsToReturn): void
    {
        foreach ($coinsToReturn as $coinValue => $coinQuantityToReturn) {
            $isUpdated = $this->updateCoinQuantityService->execute($coinValue, $coinQuantityToReturn);

            if (!$isUpdated) {
                throw new NotSavedException(['coin' => 'The coin quantity has not been updated']);
            }
        }
    }
}
