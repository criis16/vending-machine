<?php

namespace Tests\Unit\Application\Status\Adapters;

use App\Domain\Status\Status;
use App\Domain\Status\StatusId;
use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\Adapters\StatusAdapter;

class StatusAdapterTest extends TestCase
{
    private StatusAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new StatusAdapter();
    }

    /**
     * @dataProvider statusAdapterProvider
     */
    public function testStatusAdapter(
        int $statusIdValue,
        float $statusBalanceValue,
        array $expectedResult
    ): void {
        /** @var StatusId&MockObject */
        $statusId = self::createMock(StatusId::class);

        /** @var StatusBalance&MockObject */
        $statusBalance = self::createMock(StatusBalance::class);

        $statusId->expects(self::once())
            ->method('getValue')
            ->willReturn($statusIdValue);

        $statusBalance->expects(self::once())
            ->method('getValue')
            ->willReturn($statusBalanceValue);

        /** @var Status&MockObject */
        $statusInput = self::createMock(Status::class);
        $statusInput->expects(self::once())
            ->method('getStatusId')
            ->willReturn($statusId);

        $statusInput->expects(self::once())
            ->method('getStatusBalance')
            ->willReturn($statusBalance);

        $this->assertEquals($expectedResult, $this->sut->adapt($statusInput));
    }

    public static function statusAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private static function simpleCase(): array
    {
        $statusIdValue = 23;
        $statusBalanceValue = 1.65;

        return [
            'status_id_value' => $statusIdValue,
            'status_balance_value' => $statusBalanceValue,
            'expected_output' => [
                'id' => $statusIdValue,
                'balance' => $statusBalanceValue
            ]
        ];
    }
}
