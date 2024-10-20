<?php

namespace Tests\Unit\Domain\Item;

use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;

class ItemQuantityTest extends TestCase
{
    private ItemQuantity $sut;

    /**
     * @dataProvider createItemQuantityProvider
     */
    public function testCreateItemQuantity(
        int $inputItemQuantity,
        int $expectedResult
    ): void {
        $this->sut = new ItemQuantity($inputItemQuantity);
        $this->assertEquals($expectedResult, $this->sut->getValue());
    }

    public static function createItemQuantityProvider(): array
    {
        return [
            'positive_quantity_case' => self::positiveQuantityCase(),
            'negative_quantity_case' => self::negativeQuantityCase()
        ];
    }

    private static function positiveQuantityCase(): array
    {
        return [
            'input_item_quantity' => 10,
            'expected_output' => 10
        ];
    }

    private static function negativeQuantityCase(): array
    {
        return [
            'input_item_quantity' => -5,
            'expected_output' => 0
        ];
    }
}
