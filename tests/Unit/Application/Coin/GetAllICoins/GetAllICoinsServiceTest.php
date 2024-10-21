<?php

namespace Tests\Unit\Application\Coin\GetAllCoins;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\Adapters\CoinAdapter;
use App\Application\Coin\GetAllCoins\GetAllCoinsService;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\CoinValue;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class GetAllICoinsServiceTest extends TestCase
{
    private GetAllCoinsService $sut;

    /** @var CoinRepositoryInterface&MockObject */
    private CoinRepositoryInterface $repository;

    /** @var CoinAdapter&MockObject */
    private CoinAdapter $adapter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CoinRepositoryInterface::class);
        $this->adapter = $this->createMock(CoinAdapter::class);

        $this->sut = new GetAllCoinsService(
            $this->repository,
            $this->adapter
        );
    }

    /**
     * @dataProvider getAllCoinsProvider
     */
    public function testExecute(
        array $getAllCoinsRepositoryOutput,
        array $adapterOutput,
        array $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getAllCoins')
            ->willReturn($getAllCoinsRepositoryOutput);
        $this->adapter->expects(self::exactly(\count($getAllCoinsRepositoryOutput)))
            ->method('adapt')
            ->willReturn($adapterOutput);

        $this->assertEquals($expectedResult, $this->sut->execute());
    }

    public static function getAllCoinsProvider(): array
    {
        return [
            'empty_case' => self::emptyCase(),
            'simple_case' => self::simpleCase(),
            'multiple_case' => self::multipleCase()
        ];
    }

    private static function emptyCase(): array
    {
        return [
            'get_all_coins_repository_output' => [],
            'adapter_output' => [],
            'expected_output' => []
        ];
    }

    private static function simpleCase(): array
    {
        $coin = new Coin(
            new CoinValue(0.05),
            new CoinQuantity(10)
        );

        $adaptedCoin = ['an adapted coin'];

        return [
            'get_all_coins_repository_output' => [
                $coin
            ],
            'adapter_output' => $adaptedCoin,
            'expected_output' => [$adaptedCoin]
        ];
    }

    private static function multipleCase(): array
    {
        $coin = new Coin(
            new CoinValue(0.05),
            new CoinQuantity(10)
        );

        $adaptedCoin = ['an adapted coin'];

        return [
            'get_all_coins_repository_output' => [
                $coin,
                $coin
            ],
            'adapter_output' => $adaptedCoin,
            'expected_output' => [$adaptedCoin, $adaptedCoin]
        ];
    }
}
