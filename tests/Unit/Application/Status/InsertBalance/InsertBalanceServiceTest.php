<?php

namespace Tests\Unit\Application\Status\GetStatus;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\GetStatus\GetStatusService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Status\CreateStatus\CreateStatusService;
use App\Application\Status\InsertBalance\InsertBalanceService;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Status\Exceptions\BalanceNotSavedException;

class InsertBalanceServiceTest extends TestCase
{
    private InsertBalanceService $sut;

    /** @var GetStatusService&MockObject */
    private GetStatusService $getStatusService;

    /** @var CreateStatusService&MockObject */
    private CreateStatusService $createStatusService;

    /** @var UpdateBalanceService&MockObject */
    private UpdateBalanceService $updateBalanceService;

    protected function setUp(): void
    {
        $this->getStatusService = $this->createMock(GetStatusService::class);
        $this->createStatusService = $this->createMock(CreateStatusService::class);
        $this->updateBalanceService = $this->createMock(UpdateBalanceService::class);
        $this->sut = new InsertBalanceService(
            $this->getStatusService,
            $this->createStatusService,
            $this->updateBalanceService
        );
    }

    public function testExecuteInsertsBalanceCorrectly(): void
    {
        $insertCoinValue = 1.00;
        $emptyStatus = [];

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::once())
            ->method('getCoin')
            ->willReturn($insertCoinValue);

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn($emptyStatus);

        $this->createStatusService->expects(self::once())
            ->method('execute')
            ->with($insertCoinValue)
            ->willReturn(true);

        $this->sut->execute($request);
    }

    public function testExecuteInsertsBalanceThrowsBalanceNotSavedException(): void
    {
        $insertCoinValue = 1.00;
        $emptyStatus = [];

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::once())
            ->method('getCoin')
            ->willReturn($insertCoinValue);

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn($emptyStatus);

        $this->createStatusService->expects(self::once())
            ->method('execute')
            ->with($insertCoinValue)
            ->willReturn(false);

        $this->expectException(BalanceNotSavedException::class);
        $this->expectExceptionMessage('The given balance has not been saved');

        $this->sut->execute($request);
    }

    public function testExecuteUpdateBalanceCorrectly(): void
    {
        $insertCoinValue = 1.00;
        $currentBalanceValue = 1.50;
        $updatedBalanceValue = $currentBalanceValue + $insertCoinValue;

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::once())
            ->method('getCoin')
            ->willReturn($insertCoinValue);

        $existingStatus = [
            'balance' => $currentBalanceValue
        ];

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn([$existingStatus]);

        $this->updateBalanceService->expects(self::once())
            ->method('execute')
            ->with($updatedBalanceValue);

        $this->sut->execute($request);
    }
}
