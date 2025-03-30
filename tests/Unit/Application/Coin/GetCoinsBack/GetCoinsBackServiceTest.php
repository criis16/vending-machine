<?php

namespace Tests\Unit\Application\Coin\GetCoinsBack;

use App\Domain\Coin\Coin;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\Exceptions\CoinNotSavedException;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class GetCoinsBackServiceTest extends TestCase
{
    private GetCoinsBackService $sut;

    /** @var GetCoinByValueService&MockObject */
    private GetCoinByValueService $getCoinByValueService;

    /** @var UpdateCoinQuantityService&MockObject */
    private UpdateCoinQuantityService $updateCoinQuantityService;

    protected function setUp(): void
    {
        $this->getCoinByValueService = $this->createMock(GetCoinByValueService::class);
        $this->updateCoinQuantityService = $this->createMock(UpdateCoinQuantityService::class);

        $this->sut = new GetCoinsBackService(
            $this->getCoinByValueService,
            $this->updateCoinQuantityService
        );
    }

    public function testExecuteWorksCorrectly(): void
    {
        $inputBalance = 1.0;
        $coinQuantityValue = 1;
        $currentCoinQuantity = 1;

        $coinQuantity = $this->createMock(CoinQuantity::class);
        $coinQuantity->expects(self::exactly(2))
            ->method('getValue')
            ->willReturn($coinQuantityValue);

        $coin = $this->createMock(Coin::class);
        $coin->expects(self::exactly(2))
            ->method('getCoinQuantity')
            ->willReturn($coinQuantity);

        $this->getCoinByValueService->expects(self::exactly(5))
            ->method('execute')
            ->willReturnMap([
                [0.05, []],
                [0.10, []],
                [0.25, []],
                [1.0, [$coin]]
            ]);

        $this->updateCoinQuantityService->expects(self::once())
            ->method('execute')
            ->with(1.0, $currentCoinQuantity - $coinQuantityValue)
            ->willReturn(true);

        $this->sut->execute($inputBalance);
    }

    public function testExecuteThrowsCoinNotSavedException(): void
    {
        $this->expectException(CoinNotSavedException::class);
        $this->expectExceptionMessage('The inserted coin has not been saved');

        $inputBalance = 1.0;
        $coinQuantityValue = 1;
        $currentCoinQuantity = 1;

        $coinQuantity = $this->createMock(CoinQuantity::class);
        $coinQuantity->expects(self::exactly(2))
            ->method('getValue')
            ->willReturn($coinQuantityValue);

        $coin = $this->createMock(Coin::class);
        $coin->expects(self::exactly(2))
            ->method('getCoinQuantity')
            ->willReturn($coinQuantity);

        $this->getCoinByValueService->expects(self::exactly(5))
            ->method('execute')
            ->willReturnMap([
                [0.05, []],
                [0.10, []],
                [0.25, []],
                [1.0, [$coin]]
            ]);

        $this->updateCoinQuantityService->expects(self::once())
            ->method('execute')
            ->with(1.0, $currentCoinQuantity - $coinQuantityValue)
            ->willReturn(false);

        $this->sut->execute($inputBalance);
    }
}
