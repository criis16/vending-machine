<?php

namespace Tests\Unit\Domain\Coin;

use InvalidArgumentException;
use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;

class CoinValueTest extends TestCase
{
    private CoinValue $sut;

    public function testCreateCoinValueWithInvalidCoinValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->sut = new CoinValue(0);
    }

    /**
     * @dataProvider createCoinWithValidCoinValueProvider
     */
    public function testCreateCoinWithValidCoinValue(
        float $inputCoinValue,
        float $expectedResult
    ): void {
        $this->sut = new CoinValue($inputCoinValue);
        $this->assertEquals($expectedResult, $this->sut->getValue());
    }

    public static function createCoinWithValidCoinValueProvider(): array
    {
        return [
            '0.05_case' => self::fiveCentsCoinCase(),
            '0.10_case' => self::tenCentsCoinCase(),
            '0.25_case' => self::twentyFiveCentsCoinCase(),
            '1.00_case' => self::oneEuroCoinCase()
        ];
    }

    private static function fiveCentsCoinCase(): array
    {
        return [
            'input_coin_value' => 0.05,
            'expected_output' => 0.05
        ];
    }

    private static function tenCentsCoinCase(): array
    {
        return [
            'input_coin_value' => 0.10,
            'expected_output' => 0.10
        ];
    }

    private static function twentyFiveCentsCoinCase(): array
    {
        return [
            'input_coin_value' => 0.25,
            'expected_output' => 0.25
        ];
    }

    private static function oneEuroCoinCase(): array
    {
        return [
            'input_coin_value' => 1.00,
            'expected_output' => 1.00
        ];
    }
}
