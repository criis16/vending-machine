<?php

namespace Tests\Unit\Domain\Status;

use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;

class StatusBalanceTest extends TestCase
{
    private StatusBalance $sut;

    /**
     * @dataProvider createStatusBalanceProvider
     */
    public function testCreateStatusBalance(
        float $inputStatusBalance,
        float $expectedResult
    ): void {
        $this->sut = new StatusBalance($inputStatusBalance);
        $this->assertEquals($expectedResult, $this->sut->getValue());
    }

    public static function createStatusBalanceProvider(): array
    {
        return [
            'positive_balance_case' => self::positiveBalanceCase(),
            'negative_balance_case' => self::negativeBalanceCase()
        ];
    }

    private static function positiveBalanceCase(): array
    {
        return [
            'input_status_balance' => 1.65,
            'expected_output' => 1.65
        ];
    }

    private static function negativeBalanceCase(): array
    {
        return [
            'input_status_balance' => -0.32,
            'expected_output' => 0
        ];
    }
}
