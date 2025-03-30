<?php

namespace Tests\Unit\Application\Status\GetStatus;

use InvalidArgumentException;
use App\Domain\Status\StatusId;
use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\GetStatus\GetStatusService;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Status\Exceptions\BalanceNotSavedException;

class UpdateBalanceServiceTest extends TestCase
{
    private UpdateBalanceService $sut;

    /** @var StatusRepositoryInterface&MockObject */
    private StatusRepositoryInterface $repository;

    /** @var GetStatusService&MockObject */
    private GetStatusService $getStatusService;

    protected function setUp(): void
    {
        $this->getStatusService = $this->createMock(GetStatusService::class);
        $this->repository = $this->createMock(StatusRepositoryInterface::class);
        $this->sut = new UpdateBalanceService(
            $this->repository,
            $this->getStatusService
        );
    }

    public function testExecuteThrowsExceptionWhenNoStatusBalanceFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No status balance found in the database. Please insert coins first.');
        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn([]);
        $this->sut->execute(1.00);
    }

    public function testExecuteWorksCorrectly(): void
    {
        $statusBalanceValue = 1.00;
        $statusIdValue = 1;

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn([
                [
                    'id' => $statusIdValue,
                    'balance' => $statusBalanceValue
                ]
            ]);

        $statusBalance = new StatusBalance($statusBalanceValue);
        $statusId = new StatusId($statusIdValue);

        $this->repository->expects(self::once())
            ->method('updateStatusBalance')
            ->with($statusId, $statusBalance)
            ->willReturn(true);

        $this->sut->execute($statusBalanceValue);
    }

    public function testExecuteThrowsBalanceNotSavedException(): void
    {
        $this->expectException(BalanceNotSavedException::class);
        $this->expectExceptionMessage('The inserted balance has not been updated');
        $statusBalanceValue = 1.00;
        $statusIdValue = 1;

        $this->getStatusService->expects(self::once())
            ->method('execute')
            ->willReturn([
                [
                    'id' => $statusIdValue,
                    'balance' => $statusBalanceValue
                ]
            ]);

        $statusBalance = new StatusBalance($statusBalanceValue);
        $statusId = new StatusId($statusIdValue);

        $this->repository->expects(self::once())
            ->method('updateStatusBalance')
            ->with($statusId, $statusBalance)
            ->willReturn(false);

        $this->sut->execute($statusBalanceValue);
    }
}
