<?php

namespace Tests\Unit\Application\Coin\CreateCoin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin\CoinQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\CreateCoin\CreateCoinService;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class CreateCoinServiceTest extends TestCase
{
    private CreateCoinService $sut;

    /** @var CoinRepositoryInterface&MockObject */
    private CoinRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CoinRepositoryInterface::class);
        $this->sut = new CreateCoinService(
            $this->repository
        );
    }

    public function testExecuteWorksCorrectly(): void
    {
        $inputCoinValue = 0.05;
        $inputCoinQuantity = 10;

        $coinValue = new CoinValue($inputCoinValue);
        $coinQuantity = new CoinQuantity($inputCoinQuantity);
        $coin = new Coin($coinValue, $coinQuantity);

        $this->repository->expects(self::once())
            ->method('saveCoin')
            ->with($coin)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($inputCoinValue, $inputCoinQuantity));
    }
}
