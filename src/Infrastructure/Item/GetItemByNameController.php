<?php

namespace App\Infrastructure\Item;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Infrastructure\Shared\ValidateRequestQueryDataService;
use App\Application\Item\GetItemByName\SelectItemByNameService;

class GetItemByNameController
{
    private const EMPTY_RETURN_COINS = 'Coins returned: 0.00';
    private const ITEM_NAME_FIELD = 'item_name';
    private const REQUIRED_GET_FIELDS = [
        self::ITEM_NAME_FIELD
    ];

    private ValidateRequestQueryDataService $validator;
    private InsertItemRequest $insertItemRequest;
    private SelectItemByNameService $selectItemByNameService;

    public function __construct(
        ValidateRequestQueryDataService $validator,
        InsertItemRequest $insertItemRequest,
        SelectItemByNameService $selectItemByNameService
    ) {
        $this->validator = $validator;
        $this->insertItemRequest = $insertItemRequest;
        $this->selectItemByNameService = $selectItemByNameService;
    }

    /**
     * Returns the item with the given name
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getItem(Request $request): JsonResponse
    {
        try {
            $requestQueryData = $request->query->all();
            $this->validator->validate($requestQueryData, self::REQUIRED_GET_FIELDS);
            $this->setRequestData($requestQueryData);

            $itemName = $this->insertItemRequest->getName();
            $coinsToReturn = $this->selectItemByNameService->execute($this->insertItemRequest);

            $statusCode = 200;
            $responseMessage = 'The item has been selected successfully.';
            $result = $this->getResponseMessage($itemName, $coinsToReturn);
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
            $statusCode = 400;
            $coinsToReturn = [];
            $result = '';
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
        $this->insertItemRequest->setName(\ucfirst($requestQuery[self::ITEM_NAME_FIELD]));
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
}
