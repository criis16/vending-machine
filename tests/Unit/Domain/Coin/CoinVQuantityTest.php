<?php

namespace Tests\Unit\Domain\Coin;

use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\TestCase;

class CoinVQuantityTest extends TestCase
{
    private CoinQuantity $sut;

    /**
     * @dataProvider createCoinQuantityProvider
     */
    public function testCreateCoinQuantity(
        int $inputCoinQuantity,
        int $expectedResult
    ): void {
        $this->sut = new CoinQuantity($inputCoinQuantity);
        $this->assertEquals($expectedResult, $this->sut->getValue());
    }

    public static function createCoinQuantityProvider(): array
    {
        return [
            'positive_quantity_case' => self::positiveQuantityCase(),
            'negative_quantity_case' => self::negativeQuantityCase()
        ];
    }

    private static function positiveQuantityCase(): array
    {
        return [
            'input_coin_quantity' => 10,
            'expected_output' => 10
        ];
    }

    private static function negativeQuantityCase(): array
    {
        return [
            'input_coin_quantity' => -5,
            'expected_output' => 0
        ];
    }
}
