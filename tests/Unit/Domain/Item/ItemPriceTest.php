<?php

namespace Tests\Unit\Domain\Item;

use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;

class ItemPriceTest extends TestCase
{
    private ItemPrice $sut;

    /**
     * @dataProvider createItemPriceProvider
     */
    public function testCreateItemPrice(
        float $inputItemPrice,
        float $expectedResult
    ): void {
        $this->sut = new ItemPrice($inputItemPrice);
        $this->assertEquals($expectedResult, $this->sut->getValue());
    }

    public static function createItemPriceProvider(): array
    {
        return [
            'positive_price_case' => self::positivePriceCase(),
            'negative_negative_case' => self::negativePriceCase()
        ];
    }

    private static function positivePriceCase(): array
    {
        return [
            'input_item_price' => 1.65,
            'expected_output' => 1.65
        ];
    }

    private static function negativePriceCase(): array
    {
        return [
            'input_item_price' => -0.32,
            'expected_output' => 0
        ];
    }
}
