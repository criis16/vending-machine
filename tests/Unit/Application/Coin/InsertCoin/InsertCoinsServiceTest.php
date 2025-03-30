<?php

namespace Tests\Unit\Application\Coin\InsertCoin;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Application\Coin\InsertCoin\InsertCoinsService;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;

class InsertCoinsServiceTest extends TestCase
{
    private InsertCoinsService $sut;

    /** @var InsertCoinRequest&MockObject */
    private InsertCoinRequest $insertCoinRequest;

    /** @var InsertCoinService&MockObject */
    private InsertCoinService $insertCoinService;

    protected function setUp(): void
    {
        $this->insertCoinRequest = $this->createMock(InsertCoinRequest::class);
        $this->insertCoinService = $this->createMock(InsertCoinService::class);
        $this->sut = new InsertCoinsService(
            $this->insertCoinRequest,
            $this->insertCoinService
        );
    }

    public function testExecuteInsertsCoinCorrectly(): void
    {
        $inputCoins = [
            '0.05' => 10
        ];

        $this->insertCoinService->expects(self::once())
            ->method('execute')
            ->willReturnMap([
                [$this->insertCoinRequest]
            ]);

        $this->sut->execute($inputCoins);
    }

    public function testExecuteInsertsCoinThrowsInvalidArgumentException(): void
    {
        $inputCoins = [
            '0.05' => null
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Something is wrong with the coins. Value:0.05 Quantity:');

        $this->insertCoinService->expects(self::never())
            ->method('execute')
            ->willReturnMap([
                [$this->insertCoinRequest]
            ]);

        $this->sut->execute($inputCoins);
    }
}
