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
        if (empty($request->getName())) {
            throw new InvalidArgumentException('The item name cannot be empty.');
        }

        $isOperationDone = false;
        $quantity = (!empty($request->getQuantity())) ? $request->getQuantity() : self::INITIAL_ITEM_QUANTITY;
        $price = (!empty($request->getPrice())) ? $request->getPrice() : self::INITIAL_ITEM_PRICE[$request->getName()];
        $itemQuantity = new ItemQuantity($quantity);

        $item = $this->getItemByNameService->execute($request);

        if (empty($item)) {
            $isOperationDone = $this->createNewItem($request->getName(), $price, $quantity);
        } else {
            $item = \reset($item);
            $item = $this->createItem($item['name'], $item['price'], $item['quantity'], $item['id']);
            $isOperationDone = $this->updateItemQuantity($item, $itemQuantity);
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
     * @param Item $item
     * @param ItemQuantity $itemQuantity
     * @return boolean
     */
    private function updateItemQuantity(
        Item $item,
        ItemQuantity $itemQuantity
    ): bool {
        $itemId = $item->getItemId();
        $currentItemQuantity = $item->getItemQuantity();
        $itemQuantity->setValue($currentItemQuantity->getValue() + $itemQuantity->getValue());
        return $this->repository->updateItemQuantity($itemId, $itemQuantity);
    }
}
