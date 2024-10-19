<?php

namespace App\Application\Item\InsertItem;

use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use InvalidArgumentException;
use App\Domain\Item\ItemPrice;
use App\Domain\Item\ItemQuantity;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;

class InsertItemService
{
    private const INITIAL_ITEM_QUANTITY = 1;
    private const INITIAL_ITEM_PRICE = [
        'Water' => 0.65,
        'Juice' => 1.00,
        'Soda' => 1.50
    ];

    private ItemRepositoryInterface $repository;

    public function __construct(
        ItemRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Inserts an item
     *
     * @param InsertItemRequest $request
     * @return boolean
     */
    public function execute(
        InsertItemRequest $request
    ): bool {
        if (empty($request->getName())) {
            throw new InvalidArgumentException('The item name cannot be empty.');
        }

        $isOperationDone = false;
        $quantity = (!empty($request->getQuantity())) ? $request->getQuantity() : self::INITIAL_ITEM_QUANTITY;
        $price = (!empty($request->getPrice())) ? $request->getPrice() : self::INITIAL_ITEM_PRICE[$request->getName()];

        $itemName = new ItemName($request->getName());
        $itemPrice = new ItemPrice($price);
        $itemQuantity = new ItemQuantity($quantity);
        $item = $this->repository->getItemByName($itemName);

        if (empty($item)) {
            $item = new Item($itemName, $itemQuantity, $itemPrice);
            $isOperationDone = $this->repository->saveItem($item);
        } else {
            $item = \reset($item);
            $itemId = $item->getItemId();
            $currentItemQuantity = $item->getItemQuantity();
            $itemQuantity->setValue($currentItemQuantity->getValue() + $itemQuantity->getValue());
            $isOperationDone = $this->repository->updateItemQuantity($itemId, $itemQuantity);
        }

        return $isOperationDone;
    }
}
