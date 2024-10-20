<?php

namespace Tests\Unit\Domain\Status;

use App\Domain\Status\Status;
use App\Domain\Status\StatusId;
use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;
use PHPUnit\Framework\MockObject\MockObject;

class StatusTest extends TestCase
{
    private Status $sut;

    /** @var StatusId&MockObject */
    private StatusId $statusId;

    /** @var StatusBalance&MockObject */
    private StatusBalance $statusBalance;

    protected function setUp(): void
    {
        $this->statusId = $this->createMock(StatusId::class);
        $this->statusBalance = $this->createMock(StatusBalance::class);

        $this->sut = new Status(
            $this->statusBalance
        );

        $this->sut->setStatusId($this->statusId);
    }

    public function testGetStatusId(): void
    {
        $this->assertEquals($this->statusId, $this->sut->getStatusId());
    }

    public function testGetStatusBalance(): void
    {
        $this->assertEquals($this->statusBalance, $this->sut->getStatusBalance());
    }
}
