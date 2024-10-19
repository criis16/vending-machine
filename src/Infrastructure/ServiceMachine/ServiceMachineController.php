<?php

namespace App\Infrastructure\ServiceMachine;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Application\Item\InsertItem\InsertItemService;
use App\Application\Coin\GetAllCoins\GetAllCoinsService;
use App\Application\Item\GetAllItems\GetAllItemsService;
use App\Infrastructure\Shared\ValidateRequestDataService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Infrastructure\Shared\Exceptions\RequestException;

class ServiceMachineController
{
    private const REQUIRED_BODY_FIELDS = [
        'coins',
        'items'
    ];

    private ValidateRequestDataService $validator;
    private InsertCoinRequest $insertCoinRequest;
    private InsertCoinService $insertCoinService;
    private InsertItemRequest $insertItemRequest;
    private InsertItemService $insertItemService;
    private GetAllCoinsService $getAllCoinsService;
    private GetAllItemsService $getAllItemsService;

    public function __construct(
        ValidateRequestDataService $validator,
        InsertCoinRequest $insertCoinRequest,
        InsertCoinService $insertCoinService,
        InsertItemRequest $insertItemRequest,
        InsertItemService $insertItemService,
        GetAllCoinsService $getAllCoinsService,
        GetAllItemsService $getAllItemsService
    ) {
        $this->validator = $validator;
        $this->insertCoinRequest = $insertCoinRequest;
        $this->insertCoinService = $insertCoinService;
        $this->insertItemRequest = $insertItemRequest;
        $this->insertItemService = $insertItemService;
        $this->getAllCoinsService = $getAllCoinsService;
        $this->getAllItemsService = $getAllItemsService;
    }

    /**
     * Sets the coins and items in the machine
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceMachine(Request $request): JsonResponse
    {
        $statusCode = 400;

        try {
            $requestBody = $request->toArray();
            $this->validator->validate(
                $requestBody,
                self::REQUIRED_BODY_FIELDS,
                \count(self::REQUIRED_BODY_FIELDS)
            );

            $areCoinsSaved = $this->saveCoins($requestBody['coins']);
            $areItemsSaved = $this->saveItems($requestBody['items']);

            if ($areCoinsSaved && $areItemsSaved) {
                $statusCode = 200;
                $responseMessage = 'The inserted coins and items have been saved successfully.';
            }
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
        }

        $coins = $this->getAllCoinsService->execute();
        $items = $this->getAllItemsService->execute();

        return new JsonResponse(
            [
                'message' => $responseMessage,
                'result' => $this->getStatusResponseMessage($coins, $items)
            ],
            $statusCode
        );
    }

    /**
     * Saves the given coins
     *
     * @param array $coins
     * @return boolean
     */
    private function saveCoins(array $coins): bool
    {
        foreach ($coins as $coinValue => $coinQuantity) {
            if (empty($coinValue) || empty($coinQuantity)) {
                throw new RequestException(['coins' => 'Coins must be a valid value']);
            }

            $this->insertCoinRequest->setCoin($coinValue);
            $this->insertCoinRequest->setQuantity($coinQuantity);
            $isCoinSaved = $this->insertCoinService->execute($this->insertCoinRequest);

            if (!$isCoinSaved) {
                return false;
            }
        }
        return true;
    }

    /**
     * Saves the given items
     *
     * @param array $items
     * @return boolean
     */
    private function saveItems(array $items): bool
    {
        foreach ($items as $itemName => $itemQuantity) {
            if (empty($itemName) || empty($itemQuantity)) {
                throw new RequestException(['items' => 'Items must be a valid value']);
            }

            $this->insertItemRequest->setName($itemName);
            $this->insertItemRequest->setQuantity($itemQuantity);
            $isItemSaved = $this->insertItemService->execute($this->insertItemRequest);

            if (!$isItemSaved) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the global response message of the status machine
     *
     * @param array $coins
     * @param array $items
     * @return string
     */
    private function getStatusResponseMessage(array $coins, array $items): string
    {
        if (empty($coins)) {
            $coinsMessage = 'There are no coins.';
        } else {
            $coinsMessage = 'Coins: ' . $this->getCoinsResponseMessage($coins);
        }

        if (empty($items)) {
            $itemsMessage = 'There are no items.';
        } else {
            $itemsMessage = 'Items: ' . $this->getItemsResponseMessage($items);
        }

        return $coinsMessage . ' || ' . $itemsMessage;
    }

    /**
     * Returns the response message of the coins
     *
     * @param array $coins
     * @return string
     */
    private function getCoinsResponseMessage(array $coins): string
    {
        $result = [];

        foreach ($coins as $coin) {
            $result[] = $coin['value'] . ' => Quantity: ' . $coin['quantity'];
        }

        return \implode('; ', $result);
    }

    /**
     * Returns the response message of the items
     *
     * @param array $items
     * @return string
     */
    private function getItemsResponseMessage(array $items): string
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = $item['name'] . ' => Quantity: ' .
                $item['quantity'] . ', Price: ' . \number_format($item['price'], 2);
        }

        return \implode('; ', $result);
    }
}
