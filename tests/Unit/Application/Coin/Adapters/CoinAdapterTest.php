<?php

namespace Tests\Unit\Application\Coin\Adapters;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\Adapters\CoinAdapter;

class CoinAdapterTest extends TestCase
{
    private CoinAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new CoinAdapter();
    }

    /**
     * @dataProvider coinAdapterProvider
     */
    public function testCoinAdapter(
        float $coinValueValue,
        int $coinQuantityValue,
        array $expectedResult
    ): void {
        /** @var CoinValue&MockObject */
        $coinValue = self::createMock(CoinValue::class);
        $coinValue->expects(self::once())
            ->method('getValue')
            ->willReturn($coinValueValue);

        /** @var CoinQuantity&MockObject */
        $coinQuantity = self::createMock(CoinQuantity::class);
        $coinQuantity->expects(self::once())
            ->method('getValue')
            ->willReturn($coinQuantityValue);

        /** @var Coin&MockObject */
        $coin = self::createMock(Coin::class);
        $coin->expects(self::once())
            ->method('getCoinValue')
            ->willReturn($coinValue);
        $coin->expects(self::once())
            ->method('getCoinQuantity')
            ->willReturn($coinQuantity);

        $this->assertEquals($expectedResult, $this->sut->adapt($coin));
    }

    public static function coinAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private static function simpleCase(): array
    {
        $coinValueValue = 1.65;
        $coinQuantityValue = 10;

        return [
            'coin_value_value' => $coinValueValue,
            'coin_quantity_value' => $coinQuantityValue,
            'expected_output' => [
                'value' => $coinValueValue,
                'quantity' => $coinQuantityValue
            ]
        ];
    }
}
