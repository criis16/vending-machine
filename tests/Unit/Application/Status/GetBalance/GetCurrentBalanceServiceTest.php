<?php

namespace Tests\Unit\Application\Status\GetBalance;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\GetBalance\GetBalanceService;
use App\Application\Status\Exceptions\EmptyBalanceException;
use App\Application\Status\GetBalance\GetCurrentBalanceService;

class GetCurrentBalanceServiceTest extends TestCase
{
    private GetCurrentBalanceService $sut;

    /** @var GetBalanceService&MockObject */
    private GetBalanceService $getBalanceService;

    protected function setUp(): void
    {
        $this->getBalanceService = $this->createMock(GetBalanceService::class);

        $this->sut = new GetCurrentBalanceService(
            $this->getBalanceService
        );
    }

    public function testExecuteWorksCorrctly(): void
    {
        $currentBalance = 1.00;

        $this->getBalanceService->expects(self::once())
            ->method('execute')
            ->willReturn($currentBalance);

        $this->assertEquals($currentBalance, $this->sut->execute());
    }

    public function testExecuteThrowsEmptyBalanceException(): void
    {
        $this->expectException(EmptyBalanceException::class);
        $this->expectExceptionMessage('The current balance is empty. Please insert coins first.');
        $this->sut->execute();
    }
}
