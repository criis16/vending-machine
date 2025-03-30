<?php

namespace Tests\Unit\Application\Coin\InsertCoin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\CreateCoin\CreateCoinService;
use App\Application\Coin\InsertCoin\InsertCoinService;
use App\Application\Coin\Exceptions\CoinNotSavedException;
use App\Infrastructure\Coin\Repositories\InsertCoinRequest;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class InsertCoinServiceTest extends TestCase
{
    private InsertCoinService $sut;

    /** @var GetCoinByValueService&MockObject */
    private GetCoinByValueService $getCoinByValueService;

    /** @var CreateCoinService&MockObject */
    private CreateCoinService $createCoinService;

    /** @var UpdateCoinQuantityService&MockObject */
    private UpdateCoinQuantityService $updateCoinQuantityService;

    protected function setUp(): void
    {
        $this->getCoinByValueService = $this->createMock(GetCoinByValueService::class);
        $this->createCoinService = $this->createMock(CreateCoinService::class);
        $this->updateCoinQuantityService = $this->createMock(UpdateCoinQuantityService::class);
        $this->sut = new InsertCoinService(
            $this->getCoinByValueService,
            $this->createCoinService,
            $this->updateCoinQuantityService
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

        $this->createCoinService->expects(self::once())
            ->method('execute')
            ->with($inputCoinValue, $inputCoinQuantity)
            ->willReturn(true);

        $this->sut->execute($request);
    }

    public function testExecuteInsertsCoinThrowsException(): void
    {
        $inputCoinValue = 0.05;
        $inputCoinQuantity = 10;

        $this->expectException(CoinNotSavedException::class);
        $this->expectExceptionMessage('The inserted coin has not been saved');

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

        $this->createCoinService->expects(self::once())
            ->method('execute')
            ->with($inputCoinValue, $inputCoinQuantity)
            ->willReturn(false);

        $this->sut->execute($request);
    }

    public function testExecuteUpdateCoinQuantityCorrectly(): void
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
            ->willReturn(['a coin']);

        $this->updateCoinQuantityService->expects(self::once())
            ->method('execute')
            ->with($inputCoinValue, $inputCoinQuantity)
            ->willReturn(true);

        $this->sut->execute($request);
    }

    public function testExecuteUpdateCoinQuantityThrowsException(): void
    {
        $inputCoinValue = 0.05;
        $inputCoinQuantity = 10;

        $this->expectException(CoinNotSavedException::class);
        $this->expectExceptionMessage('The inserted coin has not been saved');

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
            ->willReturn(['a coin']);

        $this->updateCoinQuantityService->expects(self::once())
            ->method('execute')
            ->with($inputCoinValue, $inputCoinQuantity)
            ->willReturn(false);

        $this->sut->execute($request);
    }
}
