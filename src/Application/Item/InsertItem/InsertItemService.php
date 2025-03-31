<?php

namespace App\Application\Item\InsertItem;

use App\Domain\Item\Item;
use App\Application\Item\CreateItem\CreateItemService;
use App\Application\Item\Exceptions\ItemNotSavedException;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;
use App\Application\Item\UpdateQuantity\UpdateQuantityService;

class InsertItemService
{
    private const QUANTITY_FIELD = 'quantity';

    private GetItemByNameService $getItemByNameService;
    private CreateItemService $createItemService;
    private UpdateQuantityService $updateQuantityService;

    public function __construct(
        GetItemByNameService $getItemByNameService,
        CreateItemService $createItemService,
        UpdateQuantityService $updateQuantityService
    ) {
        $this->getItemByNameService = $getItemByNameService;
        $this->createItemService = $createItemService;
        $this->updateQuantityService = $updateQuantityService;
    }

    /**
     * Inserts an item
     *
     * @param InsertItemRequest $request
     * @throws ItemNotSavedException
     * @return boolean
     */
    public function execute(
        InsertItemRequest $request
    ): void {
        $isOperationDone = false;
        $itemName = $request->getName();
        $itemQuantity = $request->getQuantity();

        $item = $this->getItemByNameService->execute($request);

        if (empty($item)) {
            $itemPrice = $request->getPrice();
            $price = (!empty($itemPrice)) ? $itemPrice : Item::ITEMS_INFO[$itemName];
            $isOperationDone = $this->createItemService->execute($itemName, $price, $itemQuantity);
        } else {
            $item = \reset($item);
            $isOperationDone = $this->updateQuantityService->execute(
                $request,
                $item[self::QUANTITY_FIELD] + $itemQuantity
            );
        }

        if (!$isOperationDone) {
            throw new ItemNotSavedException('The inserted item has not been saved');
        }
    }
}
