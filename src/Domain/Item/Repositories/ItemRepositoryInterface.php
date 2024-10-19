<?php

namespace App\Domain\Item\Repositories;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemQuantity;

interface ItemRepositoryInterface
{
    /**
     * Returns all the items
     *
     * @return array
     */
    public function getAllItems(): array;

    /**
     * Returns the item by name
     *
     * @param ItemName $itemName
     * @return array
     */
    public function getItemByName(ItemName $itemName): array;

    /**
     * Save the given item
     *
     * @param Item $item
     * @return boolean
     */
    public function saveItem(Item $item): bool;

    /**
     * Update the given item quantity
     *
     * @param ItemId $itemId
     * @param ItemQuantity $itemQuantity
     * @return boolean
     */
    public function updateItemQuantity(
        ItemId $itemId,
        ItemQuantity $itemQuantity
    ): bool;
}
