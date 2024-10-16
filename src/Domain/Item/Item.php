<?php

namespace App\Domain\Item;

class Item
{
    private ItemId $itemId;
    private ItemName $itemName;
    private ItemQuantity $itemQuantity;
    private ItemPrice $itemPrice;

    public function __construct(
        ItemId $itemId,
        ItemName $itemName,
        ItemQuantity $itemQuantity,
        ItemPrice $itemPrice
    ) {
        $this->itemId = $itemId;
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
