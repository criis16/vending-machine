<?php

namespace App\Application\Item\InsertItem;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use InvalidArgumentException;
use App\Domain\Item\ItemPrice;
use App\Domain\Item\ItemQuantity;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;

class InsertItemService
{
    private const INITIAL_ITEM_QUANTITY = 1;
    private const INITIAL_ITEM_PRICE = [
        'Water' => 0.65,
        'Juice' => 1.00,
        'Soda' => 1.50
    ];

    private ItemRepositoryInterface $repository;
    private GetItemByNameService $getItemByNameService;

    public function __construct(
        ItemRepositoryInterface $repository,
        GetItemByNameService $getItemByNameService
    ) {
        $this->repository = $repository;
        $this->getItemByNameService = $getItemByNameService;
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
        $itemName = $request->getName();
        if (empty($itemName)) {
            throw new InvalidArgumentException('The item name cannot be empty.');
        }

        $itemQuantity = $request->getQuantity();
        $isOperationDone = false;
        $quantity = (!empty($itemQuantity)) ? $itemQuantity : self::INITIAL_ITEM_QUANTITY;
        $item = $this->getItemByNameService->execute($request);

        if (empty($item)) {
            $itemPrice = $request->getPrice();
            $price = (!empty($itemPrice)) ? $itemPrice : self::INITIAL_ITEM_PRICE[$itemName];
            $isOperationDone = $this->createNewItem($itemName, $price, $quantity);
        } else {
            $item = \reset($item);
            $isOperationDone = $this->updateItemQuantity(
                $item['id'],
                $item['quantity'],
                $quantity
            );
        }

        return $isOperationDone;
    }

    /**
     * Creates a new item object
     *
     * @param string $name
     * @param float $price
     * @param integer $quantity
     * @param integer|null $id
     * @return Item
     */
    private function createItem(
        string $name,
        float $price,
        int $quantity,
        ?int $id = null
    ): Item {
        $itemName = new ItemName($name);
        $itemPrice = new ItemPrice($price);
        $itemQuantity = new ItemQuantity($quantity);

        $item = new Item($itemName, $itemQuantity, $itemPrice);

        if (!empty($id)) {
            $item->setItemId(new ItemId($id));
        }

        return $item;
    }

    /**
     * Creates a new item in the database
     *
     * @param string $itemName
     * @param float $itemPrice
     * @param integer $itemQuantity
     * @return boolean
     */
    private function createNewItem(
        string $itemName,
        float $itemPrice,
        int $itemQuantity
    ): bool {
        return $this->repository->saveItem(
            $this->createItem($itemName, $itemPrice, $itemQuantity)
        );
    }

    /**
     * Updates the item quantity
     *
     * @param integer $itemId
     * @param integer $currentQuantity
     * @param integer $newQuantity
     * @return boolean
     */
    private function updateItemQuantity(
        int $itemId,
        int $currentQuantity,
        int $newQuantity
    ): bool {
        $itemId = new ItemId($itemId);
        $itemQuantity = new ItemQuantity($currentQuantity + $newQuantity);
        return $this->repository->updateItemQuantity($itemId, $itemQuantity);
    }
}
