<?php

namespace Tests\Unit\Domain\Item;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;

class ItemTest extends TestCase
{
    private Item $sut;

    /** @var ItemId&MockObject */
    private ItemId $itemId;

    /** @var ItemName&MockObject */
    private ItemName $itemName;

    /** @var ItemQuantity&MockObject */
    private ItemQuantity $itemQuantity;

    /** @var ItemPrice&MockObject */
    private ItemPrice $itemPrice;

    protected function setUp(): void
    {
        $this->itemId = $this->createMock(ItemId::class);
        $this->itemName = $this->createMock(ItemName::class);
        $this->itemPrice = $this->createMock(ItemPrice::class);
        $this->itemQuantity = $this->createMock(ItemQuantity::class);

        $this->sut = new Item(
            $this->itemName,
            $this->itemQuantity,
            $this->itemPrice
        );

        $this->sut->setItemId($this->itemId);
    }

    public function testGetItemId(): void
    {
        $this->assertEquals($this->itemId, $this->sut->getItemId());
    }

    public function testGetItemName(): void
    {
        $this->assertEquals($this->itemName, $this->sut->getItemName());
    }

    public function testGetItemQuantity(): void
    {
        $this->assertEquals($this->itemQuantity, $this->sut->getItemQuantity());
    }

    public function testGetItemPrice(): void
    {
        $this->assertEquals($this->itemPrice, $this->sut->getItemPrice());
    }
}
