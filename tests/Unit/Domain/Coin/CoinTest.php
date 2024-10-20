<?php

namespace Tests\Unit\Domain\Coin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;

class CoinTest extends TestCase
{
    private Coin $sut;

    /** @var CoinId&MockObject */
    private CoinId $coinId;

    /** @var CoinValue&MockObject */
    private CoinValue $coinValue;

    /** @var CoinQuantity&MockObject */
    private CoinQuantity $coinQuantity;

    protected function setUp(): void
    {
        $this->coinId = $this->createMock(CoinId::class);
        $this->coinValue = $this->createMock(CoinValue::class);
        $this->coinQuantity = $this->createMock(CoinQuantity::class);

        $this->sut = new Coin(
            $this->coinValue,
            $this->coinQuantity
        );

        $this->sut->setCoinId($this->coinId);
    }

    public function testGetCoinId(): void
    {
        $this->assertEquals($this->coinId, $this->sut->getCoinId());
    }

    public function testGetCoinValue(): void
    {
        $this->assertEquals($this->coinValue, $this->sut->getCoinValue());
    }

    public function testGetCoinQuantity(): void
    {
        $this->assertEquals($this->coinQuantity, $this->sut->getCoinQuantity());
    }
}
