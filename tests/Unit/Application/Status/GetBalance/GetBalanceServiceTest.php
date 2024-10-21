<?php

namespace Tests\Unit\Application\Status\GetBalance;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\GetStatus\GetStatusService;
use App\Application\Status\GetBalance\GetBalanceService;

class GetBalanceServiceTest extends TestCase
{
    private GetBalanceService $sut;

    /** @var GetStatusService&MockObject */
    private GetStatusService $getStatusService;

    protected function setUp(): void
    {
        $this->getStatusService = $this->createMock(GetStatusService::class);

        $this->sut = new GetBalanceService(
            $this->getStatusService
        );
    }

    /**
     * @dataProvider getBalanceProvider
     */
    public function testExecute(
        array $getStatusServiceOutput,
        float $expectedResult
    ): void {
        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn($getStatusServiceOutput);

        $this->assertEquals($expectedResult, $this->sut->execute());
    }

    public static function getBalanceProvider(): array
    {
        return [
            'empty_case' => self::emptyCase(),
            'simple_case' => self::simpleCase()
        ];
    }

    private static function emptyCase(): array
    {
        return [
            'get_status_service_output' => [],
            'expected_output' => 0.0
        ];
    }

    private static function simpleCase(): array
    {
        $statusBalanceValue = 1.65;
        return [
            'get_status_service_output' => [
                ['balance' => $statusBalanceValue]
            ],
            'expected_output' => $statusBalanceValue
        ];
    }
}
