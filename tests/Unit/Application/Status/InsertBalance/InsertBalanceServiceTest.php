<?php

namespace Tests\Unit\Application\Status\GetStatus;

use InvalidArgumentException;
use App\Domain\Status\StatusId;
use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\GetStatus\GetStatusService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use App\Application\Status\InsertBalance\InsertBalanceService;
use App\Domain\Status\Status;

class InsertBalanceServiceTest extends TestCase
{
    private InsertBalanceService $sut;

    /** @var StatusRepositoryInterface&MockObject */
    private StatusRepositoryInterface $repository;

    /** @var GetStatusService&MockObject */
    private GetStatusService $getStatusService;

    protected function setUp(): void
    {
        $this->getStatusService = $this->createMock(GetStatusService::class);
        $this->repository = $this->createMock(StatusRepositoryInterface::class);
        $this->sut = new InsertBalanceService(
            $this->repository,
            $this->getStatusService
        );
    }

    public function testExecuteThrowsExceptionWhenNoCoinInsertedFound(): void
    {
        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::once())
            ->method('getCoin')
            ->willReturn(0.00);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The coin value is not valid.');
        $this->sut->execute($request);
    }

    public function testExecuteInsertsBalanceCorrectly(): void
    {
        $insertCoinValue = 1.00;
        $currentBalanceValue = 1.00;
        $emptyStatus = [];

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::exactly(2))
            ->method('getCoin')
            ->willReturn($insertCoinValue, $insertCoinValue);

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn($emptyStatus);

        $statusBalance = new StatusBalance($currentBalanceValue);
        $status = new Status($statusBalance);

        $this->repository->expects(self::once())
            ->method('saveStatus')
            ->with($status)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request));
    }

    public function testExecuteUpdateBalanceCorrectly(): void
    {
        $insertCoinValue = 1.00;
        $currentBalanceValue = 1.50;
        $updatedBalanceValue = $currentBalanceValue + $insertCoinValue;

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::exactly(2))
            ->method('getCoin')
            ->willReturn($insertCoinValue, $insertCoinValue);

        $existingStatus = [
            'balance' => $currentBalanceValue,
            'id' => 1
        ];

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn([$existingStatus]);

        $statusId = new StatusId($existingStatus['id']);
        $statusBalance = new StatusBalance($updatedBalanceValue);

        $this->repository->expects(self::once())
            ->method('updateStatusBalance')
            ->with($statusId, $statusBalance)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request));
    }
}
