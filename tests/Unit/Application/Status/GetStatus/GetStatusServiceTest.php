<?php

namespace Tests\Unit\Application\Status\GetStatus;

use App\Application\Status\Adapters\StatusAdapter;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\GetStatus\GetStatusService;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use App\Domain\Status\Status;

class GetStatusServiceTest extends TestCase
{
    private GetStatusService $sut;

    /** @var StatusRepositoryInterface&MockObject */
    private StatusRepositoryInterface $repository;

    /** @var StatusAdapter&MockObject */
    private StatusAdapter $adapter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(StatusRepositoryInterface::class);
        $this->adapter = $this->createMock(StatusAdapter::class);

        $this->sut = new GetStatusService(
            $this->repository,
            $this->adapter
        );
    }

    public function testExecuteWithSingleStatus(): void
    {
        $status = $this->createMock(Status::class);
        $getStatusOutput = [$status];
        $adaptOutput = ['a status array adapted'];
        $expectedResult = [
            $adaptOutput
        ];

        $this->repository->expects(self::once())
            ->method('getStatus')
            ->willReturn($getStatusOutput);

        $this->adapter->expects(self::exactly(\count($getStatusOutput)))
            ->method('adapt')
            ->willReturn($adaptOutput);

        $this->assertEquals($expectedResult, $this->sut->execute());
    }

    public function testExecuteWithMultipleStatus(): void
    {
        $status = $this->createMock(Status::class);
        $getStatusOutput = [
            $status,
            $status
        ];
        $adaptOutput = ['a status array adapted'];
        $expectedResult = [
            $adaptOutput,
            $adaptOutput
        ];

        $this->repository->expects(self::once())
            ->method('getStatus')
            ->willReturn($getStatusOutput);

        $this->adapter->expects(self::exactly(\count($getStatusOutput)))
            ->method('adapt')
            ->willReturn($adaptOutput);

        $this->assertEquals($expectedResult, $this->sut->execute());
    }
}
