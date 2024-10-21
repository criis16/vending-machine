<?php

namespace Tests\Unit\Application\Item\Adapters;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\Adapters\ItemAdapter;

class ItemAdapterTest extends TestCase
{
    private ItemAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new ItemAdapter();
    }

    /**
     * @dataProvider itemAdapterProvider
     */
    public function testItemAdapter(
        int $itemIdValue,
        string $itemNameValue,
        int $itemQuantityValue,
        float $itemPriceValue,
        array $expectedResult
    ): void {
        /** @var ItemId&MockObject */
        $itemId = self::createMock(ItemId::class);
        $itemId->expects(self::once())
            ->method('getValue')
            ->willReturn($itemIdValue);

        /** @var ItemName&MockObject */
        $itemName = self::createMock(ItemName::class);
        $itemName->expects(self::once())
            ->method('getValue')
            ->willReturn($itemNameValue);

        /** @var ItemPrice&MockObject */
        $itemPrice = self::createMock(ItemPrice::class);
        $itemPrice->expects(self::once())
            ->method('getValue')
            ->willReturn($itemPriceValue);

        /** @var ItemQuantity&MockObject */
        $itemQuantity = self::createMock(ItemQuantity::class);
        $itemQuantity->expects(self::once())
            ->method('getValue')
            ->willReturn($itemQuantityValue);

        /** @var Item&MockObject */
        $itemInput = self::createMock(Item::class);
        $itemInput->expects(self::once())
            ->method('getItemId')
            ->willReturn($itemId);
        $itemInput->expects(self::once())
            ->method('getItemName')
            ->willReturn($itemName);
        $itemInput->expects(self::once())
            ->method('getItemQuantity')
            ->willReturn($itemQuantity);
        $itemInput->expects(self::once())
            ->method('getItemPrice')
            ->willReturn($itemPrice);

        $this->assertEquals($expectedResult, $this->sut->adapt($itemInput));
    }

    public static function itemAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private static function simpleCase(): array
    {
        $itemIdValue = 99;
        $itemNameValue = 'an item name';
        $itemQuantityValue = 10;
        $itemPriceValue = 1.65;

        return [
            'item_id_value' => $itemIdValue,
            'item_name_value' => $itemNameValue,
            'item_quantity_value' => $itemQuantityValue,
            'item_price_value' => $itemPriceValue,
            'expected_output' => [
                'id' => $itemIdValue,
                'name' => $itemNameValue,
                'quantity' => $itemQuantityValue,
                'price' => $itemPriceValue
            ]
        ];
    }
}
