<?php

namespace Tests\Unit\Application\Item\Adapters;

use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\Adapters\EntityItemAdapter;
use App\Domain\Item\ItemId;
use App\Entity\Item as EntityItem;

class EntityItemAdapterTest extends TestCase
{
    private EntityItemAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new EntityItemAdapter();
    }

    /**
     * @dataProvider entityItemAdapterProvider
     */
    public function testEntityItemAdapter(
        int $entityItemId,
        string $entityItemName,
        int $entityItemQuantity,
        float $entityItemPrice,
        Item $expectedResult
    ): void {
        /** @var EntityItem&MockObject */
        $entityItem = $this->createMock(EntityItem::class);
        $entityItem->expects(self::once())
            ->method('getId')
            ->willReturn($entityItemId);
        $entityItem->expects(self::once())
            ->method('getName')
            ->willReturn($entityItemName);
        $entityItem->expects(self::once())
            ->method('getQuantity')
            ->willReturn($entityItemQuantity);
        $entityItem->expects(self::once())
            ->method('getPrice')
            ->willReturn($entityItemPrice);

        $this->assertEquals($expectedResult, $this->sut->adapt($entityItem));
    }

    public static function entityItemAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private static function simpleCase(): array
    {
        $entityItemIdValue = 23;
        $entityItemNameValue = 'water';
        $entityItemQuantityValue = 10;
        $entityItemPriceValue = 1.65;

        $item = new Item(
            new ItemName($entityItemNameValue),
            new ItemQuantity($entityItemQuantityValue),
            new ItemPrice($entityItemPriceValue)
        );

        $item->setItemId(
            new ItemId($entityItemIdValue)
        );

        return [
            'entity_item_id_value' => $entityItemIdValue,
            'entity_item_name_value' => $entityItemNameValue,
            'entity_item_quantity_value' => $entityItemQuantityValue,
            'entity_item_price_value' => $entityItemPriceValue,
            'expected_output' => $item
        ];
    }
}
