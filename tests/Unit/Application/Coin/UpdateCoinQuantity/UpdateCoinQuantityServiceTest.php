<?php

namespace Tests\Unit\Application\Coin\UpdateCoinQuantity;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Application\Coin\UpdateCoinQuantity\UpdateCoinQuantityService;

class UpdateCoinQuantityServiceTest extends TestCase
{
    private UpdateCoinQuantityService $sut;

    /** @var CoinRepositoryInterface&MockObject */
    private CoinRepositoryInterface $repository;

    /** @var GetCoinByValueService&MockObject */
    private GetCoinByValueService $getCoinByValueService;

    protected function setUp(): void
    {
        $this->getCoinByValueService = $this->createMock(GetCoinByValueService::class);
        $this->repository = $this->createMock(CoinRepositoryInterface::class);
        $this->sut = new UpdateCoinQuantityService(
            $this->repository,
            $this->getCoinByValueService
        );
    }

    public function testExecuteThrowsExceptionWhenNotCoinFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No coin found with the given value 0.05');
        $this->sut->execute(0.05, 10);
    }

    public function testExecuteWorksCorrectly(): void
    {
        $inputCoinValue = 0.05;
        $inputCoinQuantity = 1;
        $currenCoinQuantityValue = 10;
        $updatedCoinQuantityValue = $currenCoinQuantityValue - $inputCoinQuantity;

        /** @var CoinId&MockObject */
        $currentCoinId = $this->createMock(CoinId::class);

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

        $coinQuantity = new CoinQuantity($updatedCoinQuantityValue);

        $this->repository->expects(self::once())
            ->method('updateCoinQuantity')
            ->with($currentCoinId, $coinQuantity)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($inputCoinValue, $inputCoinQuantity));
    }
}
