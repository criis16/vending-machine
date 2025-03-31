<?php

namespace App\Application\Item\GetItemByName;

use App\Domain\Coin\CoinValue;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\Exceptions\ItemsNotReturnException;
use App\Application\Item\UpdateQuantity\UpdateQuantityService;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Status\GetBalance\GetCurrentBalanceService;

class SelectItemByNameService
{
    private const ITEM_QUANTITY_DECREASE = 1;

    private GetItemByNameService $getItemByNameService;
    private GetCurrentBalanceService $getCurrentBalanceService;
    private UpdateQuantityService $updateQuantityService;
    private UpdateBalanceService $updateBalanceService;
    private GetCoinsBackService $getCoinsBackService;

    public function __construct(
        GetItemByNameService $getItemByNameService,
        GetCurrentBalanceService $getCurrentBalanceService,
        UpdateQuantityService $updateQuantityService,
        UpdateBalanceService $updateBalanceService,
        GetCoinsBackService $getCoinsBackService
    ) {
        $this->getItemByNameService = $getItemByNameService;
        $this->getCurrentBalanceService = $getCurrentBalanceService;
        $this->updateQuantityService = $updateQuantityService;
        $this->updateBalanceService = $updateBalanceService;
        $this->getCoinsBackService = $getCoinsBackService;
    }

    /**
     * Executes the item selection process.
     *
     * @param InsertItemRequest $request
     * @throws ItemsNotReturnException
     * @return array
     */
    public function execute(
        InsertItemRequest $request
    ): array {
        $item = $this->getItemByNameService->execute($request);

        if (empty($item)) {
            throw new ItemsNotReturnException('The item does not exist. Please contact the service team.');
        }

        $item = \reset($item);
        $itemQuantity = $item['quantity'];
        if (empty($itemQuantity)) {
            throw new ItemsNotReturnException('The item is not available. Please contact the service.');
        }

        $itemPrice = $item['price'];
        $currentBalance = $this->getCurrentBalanceService->execute();
        if ($currentBalance < $itemPrice) {
            throw new ItemsNotReturnException('The balance is not enough. Please insert coins.');
        }
        $currentBalance -= $itemPrice;
        $coinsToReturn = $this->getCoinsBackService->execute($currentBalance, CoinValue::ALLOWED_RETURN_COIN_VALUES);

        $this->updateBalanceService->execute($currentBalance);
        $this->updateBalanceService->execute();
        $this->updateQuantityService->execute($request, $itemQuantity - self::ITEM_QUANTITY_DECREASE);

        return $coinsToReturn;
    }
}
