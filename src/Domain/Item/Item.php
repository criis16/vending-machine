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
     * Get the value of itemId
     *
     * @return ItemId
     */
    public function getItemId(): ItemId
    {
        return $this->itemId;
    }

    /**
     * Set the value of itemId
     *
     * @param ItemId $itemId
     * @return void
     */
    public function setItemId(ItemId $itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * Get the value of itemName
     *
     * @return ItemName
     */
    public function getItemName(): ItemName
    {
        return $this->itemName;
    }

    /**
     * Set the value of itemName
     *
     * @param ItemName $itemName
     * @return void
     */
    public function setItemName(ItemName $itemName): void
    {
        $this->itemName = $itemName;
    }

    /**
     * Get the value of itemQuantity
     *
     * @return ItemQuantity
     */
    public function getItemQuantity(): ItemQuantity
    {
        return $this->itemQuantity;
    }

    /**
     * Set the value of itemQuantity
     *
     * @param ItemQuantity $itemQuantity
     * @return void
     */
    public function setItemQuantity(ItemQuantity $itemQuantity): void
    {
        $this->itemQuantity = $itemQuantity;
    }

    /**
     * Get the value of itemPrice
     *
     * @return ItemPrice
     */
    public function getItemPrice(): ItemPrice
    {
        return $this->itemPrice;
    }

    /**
     * Set the value of itemPrice
     *
     * @param ItemPrice $itemPrice
     * @return void
     */
    public function setItemPrice(ItemPrice $itemPrice): void
    {
        $this->itemPrice = $itemPrice;
    }
}
