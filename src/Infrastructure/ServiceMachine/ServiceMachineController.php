<?php

namespace App\Infrastructure\ServiceMachine;

use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Coin\InsertCoin\InsertCoinsService;
use App\Application\Item\InsertItem\InsertItemsService;
use App\Application\Coin\GetAllCoins\GetAllCoinsService;
use App\Application\Item\GetAllItems\GetAllItemsService;
use App\Infrastructure\Shared\ValidateRequestDataService;

class ServiceMachineController
{
    private const COINS = 'coins';
    private const ITEMS = 'items';

    private const REQUIRED_BODY_FIELDS = [
        self::COINS,
        self::ITEMS
    ];

    private ValidateRequestDataService $validator;
    private GetAllCoinsService $getAllCoinsService;
    private GetAllItemsService $getAllItemsService;
    private InsertCoinsService $insertCoinsService;
    private InsertItemsService $insertItemsService;

    public function __construct(
        ValidateRequestDataService $validator,
        GetAllCoinsService $getAllCoinsService,
        GetAllItemsService $getAllItemsService,
        InsertCoinsService $insertCoinsService,
        InsertItemsService $insertItemsService
    ) {
        $this->validator = $validator;
        $this->getAllCoinsService = $getAllCoinsService;
        $this->getAllItemsService = $getAllItemsService;
        $this->insertCoinsService = $insertCoinsService;
        $this->insertItemsService = $insertItemsService;
    }

    /**
     * Sets the coins and items in the machine
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceMachine(Request $request): JsonResponse
    {
        try {
            $requestBody = $request->toArray();
            $this->validator->validate(
                $requestBody,
                self::REQUIRED_BODY_FIELDS,
                \count(self::REQUIRED_BODY_FIELDS)
            );

            $this->insertCoinsService->execute($requestBody[self::COINS]);
            $this->insertItemsService->execute($requestBody[self::ITEMS]);

            $statusCode = 200;
            $responseMessage = 'The inserted coins and items have been saved successfully.';
        } catch (Exception $e) {
            $responseMessage = $e->getMessage();
            $statusCode = 400;
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
     * Returns the global response message of the status machine
     *
     * @param array $coins
     * @param array $items
     * @throws InvalidArgumentException
     * @return string
     */
    private function getStatusResponseMessage(array $coins, array $items): string
    {
        if (empty($coins)) {
            throw new InvalidArgumentException('There are no coins');
        }

        if (empty($items)) {
            throw new InvalidArgumentException('There are no items');
        }

        $coinsMessage = 'Coins: ' . $this->getCoinsResponseMessage($coins);
        $itemsMessage = 'Items: ' . $this->getItemsResponseMessage($items);

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
