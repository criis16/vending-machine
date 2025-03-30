<?php

namespace App\Domain\Item;

class Item
{
    public const ITEMS_INFO = [
        ItemName::WATER_ITEM_NAME => ItemPrice::WATER_PRICE,
        ItemName::JUICE_ITEM_NAME => ItemPrice::JUICE_PRICE,
        ItemName::SODA_ITEM_NAME => ItemPrice::SODA_PRICE
    ];

    private ItemId $itemId;
    private ItemName $itemName;
    private ItemQuantity $itemQuantity;
    private ItemPrice $itemPrice;

    public function __construct(
        ItemName $itemName,
        ItemQuantity $itemQuantity,
        ItemPrice $itemPrice
    ) {
        $this->itemName = $itemName;
        $this->itemQuantity = $itemQuantity;
        $this->itemPrice = $itemPrice;
    }

    /**
     * Get the domain object of itemId
     *
     * @return ItemId
     */
    public function getItemId(): ItemId
    {
        return $this->itemId;
    }

    /**
     * Set the domain object of itemId
     *
     * @param ItemId $itemId
     * @return void
     */
    public function setItemId(ItemId $itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * Get the domain object of itemName
     *
     * @return ItemName
     */
    public function getItemName(): ItemName
    {
        return $this->itemName;
    }

    /**
     * Get the domain object of itemQuantity
     *
     * @return ItemQuantity
     */
    public function getItemQuantity(): ItemQuantity
    {
        return $this->itemQuantity;
    }

    /**
     * Get the domain object of itemPrice
     *
     * @return ItemPrice
     */
    public function getItemPrice(): ItemPrice
    {
        return $this->itemPrice;
    }
}
