<?php

namespace Tests\Unit\Application\Status\Adapters;

use App\Domain\Status\Status;
use App\Domain\Status\StatusId;
use PHPUnit\Framework\TestCase;
use App\Domain\Status\StatusBalance;
use App\Entity\Status as EntityStatus;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Status\Adapters\EntityStatusAdapter;

class EntityStatusAdapterTest extends TestCase
{
    private EntityStatusAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new EntityStatusAdapter();
    }

    /**
     * @dataProvider statusAdapterProvider
     */
    public function testEntityStatusAdapter(
        int $entityStatusId,
        float $entityStatusBalance,
        Status $expectedResult
    ): void {
        /** @var EntityStatus&MockObject */
        $entityStatus = $this->createMock(EntityStatus::class);
        $entityStatus->expects(self::once())
            ->method('getId')
            ->willReturn($entityStatusId);
        $entityStatus->expects(self::once())
            ->method('getBalance')
            ->willReturn($entityStatusBalance);

        $this->assertEquals($expectedResult, $this->sut->adapt($entityStatus));
    }

    public static function statusAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private static function simpleCase(): array
    {
        $entityStatusIdValue = 23;
        $entityStatusBalanceValue = 1.65;

        $status = new Status(
            new StatusBalance($entityStatusBalanceValue)
        );

        $status->setStatusId(
            new StatusId($entityStatusIdValue)
        );

        return [
            'entity_status_id_value' => $entityStatusIdValue,
            'entity_status_balance_value' => $entityStatusBalanceValue,
            'expected_output' => $status
        ];
    }
}
