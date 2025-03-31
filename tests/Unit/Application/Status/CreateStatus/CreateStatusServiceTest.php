<?php

namespace Tests\Unit\Application\Status\CreateStatus;

use App\Domain\Status\Status;
use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\CreateStatus\CreateStatusService;
use App\Domain\Status\Repositories\StatusRepositoryInterface;

class CreateStatusServiceTest extends TestCase
{
    private CreateStatusService $sut;

    /** @var StatusRepositoryInterface&MockObject */
    private StatusRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(StatusRepositoryInterface::class);
        $this->sut = new CreateStatusService(
            $this->repository
        );
    }

    public function testExecuteCorrectly(): void
    {
        $balance = 1.00;

        $status = new Status(
            new StatusBalance($balance)
        );

        $this->repository->expects(self::once())
            ->method('saveStatus')
            ->with($status)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($balance));
    }
}
