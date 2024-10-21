<?php

namespace Tests\Unit\Application\Coin\InsertCoin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;

class InsertCoinServiceTest extends TestCase
{
    private InsertCoinService $sut;

    /** @var CoinRepositoryInterface&MockObject */
    private CoinRepositoryInterface $repository;

    /** @var GetCoinByValueService&MockObject */
    private GetCoinByValueService $getCoinByValueService;

    protected function setUp(): void
    {
        $this->getCoinByValueService = $this->createMock(GetCoinByValueService::class);
        $this->repository = $this->createMock(CoinRepositoryInterface::class);
        $this->sut = new InsertCoinService(
            $this->repository,
            $this->getCoinByValueService
        );
    }

    public function testExecuteInsertsCoinCorrectly(): void
    {
        $inputCoinValue = 0.05;
        $inputCoinQuantity = 10;

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::once())
            ->method('getCoin')
            ->willReturn($inputCoinValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($inputCoinQuantity);

        $this->getCoinByValueService->expects(self::once())
            ->method('execute')
            ->with($inputCoinValue)
            ->willReturn([]);

        $coinValue = new CoinValue($inputCoinValue);
        $coinQuantity = new CoinQuantity($inputCoinQuantity);
        $coin = new Coin($coinValue, $coinQuantity);

        $this->repository->expects(self::once())
            ->method('saveCoin')
            ->with($coin)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request));
    }

    public function testExecuteUpdateCoinQuantityCorrectly(): void
    {
        $inputCoinValue = 0.05;
        $inputCoinQuantity = 10;
        $currentCoinIdValue = 23;
        $currenCoinQuantityValue = 10;
        $updatedQuantityValue = $currenCoinQuantityValue + $inputCoinQuantity;

        /** @var InsertCoinRequest&MockObject */
        $request = $this->createMock(InsertCoinRequest::class);
        $request->expects(self::once())
            ->method('getCoin')
            ->willReturn($inputCoinValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($inputCoinQuantity);

        /** @var CoinId&MockObject */
        $currentCoinId = $this->createMock(CoinId::class);
        $currentCoinId->expects(self::once())
            ->method('getValue')
            ->willReturn($currentCoinIdValue);

        /** @var CoinQuantity&MockObject */
        $currenCoinQuantity = $this->createMock(CoinQuantity::class);
        $currenCoinQuantity->expects(self::once())
            ->method('getValue')
            ->willReturn($currenCoinQuantityValue);

        /** @var Coin&MockObject */
        $coin = $this->createMock(Coin::class);
        $coin->expects(self::once())
            ->method('getCoinQuantity')
            ->willReturn($currenCoinQuantity);
        $coin->expects(self::once())
            ->method('getCoinId')
            ->willReturn($currentCoinId);

        $this->getCoinByValueService->expects(self::once())
            ->method('execute')
            ->with($inputCoinValue)
            ->willReturn([$coin]);

        $coinId = new CoinId($currentCoinIdValue);
        $coinQuantity = new CoinQuantity($updatedQuantityValue);

        $this->repository->expects(self::once())
            ->method('updateCoinQuantity')
            ->with($coinId, $coinQuantity)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request));
    }
}
