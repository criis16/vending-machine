<?php

namespace Tests\Unit\Application\Coin\Adapters;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use App\Entity\Coin as EntityCoin;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\Adapters\EntityCoinAdapter;

class EntityCoinAdapterTest extends TestCase
{
    private EntityCoinAdapter $sut;

    protected function setUp(): void
    {
        $this->sut = new EntityCoinAdapter();
    }

    /**
     * @dataProvider entityCoinAdapterProvider
     */
    public function testEntityCoinAdapter(
        float $coinValueValue,
        int $coinQuantityValue,
        int $coinIdValue,
        Coin $expectedResult
    ): void {
        /** @var EntityCoin&MockObject */
        $entityCoin = $this->createMock(EntityCoin::class);
        $entityCoin->expects(self::once())
            ->method('getId')
            ->willReturn($coinIdValue);
        $entityCoin->expects(self::once())
            ->method('getValue')
            ->willReturn($coinValueValue);
        $entityCoin->expects(self::once())
            ->method('getQuantity')
            ->willReturn($coinQuantityValue);

        $this->assertEquals($expectedResult, $this->sut->adapt($entityCoin));
    }

    public static function entityCoinAdapterProvider(): array
    {
        return [
            'simple_case' => self::simpleCase()
        ];
    }

    private static function simpleCase(): array
    {
        $coinValueValue = 0.05;
        $coinQuantityValue = 10;
        $coinIdValue = 23;

        $coin = new Coin(
            new CoinValue($coinValueValue),
            new CoinQuantity($coinQuantityValue)
        );

        $coin->setCoinId(
            new CoinId($coinIdValue)
        );

        return [
            'coin_value_value' => $coinValueValue,
            'coin_quantity_value' => $coinQuantityValue,
            'coin_id_value' => $coinIdValue,
            'expected_output' => $coin
        ];
    }
}
