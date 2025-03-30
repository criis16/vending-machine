<?php

namespace Tests\Unit\Domain\Item;

use App\Domain\Item\ItemName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ItemNameTest extends TestCase
{
    private ItemName $sut;

    public function testCreateItemName(): void
    {
        $itemNameValue = 'an item name';
        $this->sut = new ItemName($itemNameValue);
        $this->assertEquals($itemNameValue, $this->sut->getValue());
    }

    public function testCreateItemNameThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The item name cannot be empty.');

        $itemNameValue = '';
        $this->sut = new ItemName($itemNameValue);
    }
}
