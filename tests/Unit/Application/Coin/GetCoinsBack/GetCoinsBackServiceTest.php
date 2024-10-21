<?php

namespace Tests\Unit\Application\Coin\GetCoinsBack;

use App\Domain\Coin\Coin;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;

class GetCoinsBackServiceTest extends TestCase
{
    private GetCoinsBackService $sut;

    /** @var GetCoinByValueService&MockObject */
    private GetCoinByValueService $getCoinByValueService;

    protected function setUp(): void
    {
        $this->getCoinByValueService = $this->createMock(GetCoinByValueService::class);

        $this->sut = new GetCoinsBackService(
            $this->getCoinByValueService
        );
    }

    public function testExecuteThrowsExceptionWhenNotProvidedAllowedReturnCoins(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The allowed return coins empty.');
        $this->sut->execute([], 0.05);
    }

    public function testExecuteWorksCorrectly(): void
    {
        $allowedReturnCoins = [0.05];
        $inputBalance = 0.05;
        $currenCoinQuantity = 10;
        $expectedOutput = [
            '0.05' => 1
        ];

        /** @var CoinQuantity&MockObject */
        $coinQuantity = $this->createMock(CoinQuantity::class);
        $coinQuantity->expects(self::once())
            ->method('getValue')
            ->willReturn($currenCoinQuantity);

        /** @var Coin&MockObject */
        $coin = $this->createMock(Coin::class);
        $coin->expects(self::once())
            ->method('getCoinQuantity')
            ->willReturn($coinQuantity);

        $this->getCoinByValueService->expects(self::once())
            ->method('execute')
            ->with($allowedReturnCoins[0])
            ->willReturn([$coin]);

        $this->assertEquals($expectedOutput, $this->sut->execute($allowedReturnCoins, $inputBalance));
    }
}
