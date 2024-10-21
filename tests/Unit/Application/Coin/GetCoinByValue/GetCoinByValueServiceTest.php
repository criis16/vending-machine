<?php

namespace Tests\Unit\Application\Coin\GetCoinByName;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;
use App\Application\Coin\GetCoinByValue\GetCoinByValueService;
use App\Domain\Coin\CoinValue;

class GetCoinByValueServiceTest extends TestCase
{
    private GetCoinByValueService $sut;

    /** @var CoinRepositoryInterface&MockObject */
    private CoinRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CoinRepositoryInterface::class);

        $this->sut = new GetCoinByValueService(
            $this->repository
        );
    }

    public function testExecuteThrowsExceptionWhenInvalidCoinValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid coin value 0.5. Allowed values are: 0.05, 0.1, 0.25, 1');
        $this->sut->execute(0.50);
    }

    /**
     * @dataProvider getCoinByValueProvider
     */
    public function testExecute(
        float $coinValueInput,
        array $getCoinByValueOutput,
        array $expectedOutput
    ): void {
        $coinValue = new CoinValue($coinValueInput);

        $this->repository->expects(self::once())
            ->method('getCoinByValue')
            ->with($coinValue)
            ->willReturn($getCoinByValueOutput);

        $this->assertEquals($expectedOutput, $this->sut->execute($coinValueInput));
    }

    public static function getCoinByValueProvider(): array
    {
        return [
            'empty_case' => self::emptyCase(),
            'simple_case' => self::simpleCase()
        ];
    }

    private static function emptyCase(): array
    {
        return [
            'coin_value_input' => 0.05,
            'get_coin_by_value_output' => [],
            'expected_output' => []
        ];
    }

    private static function simpleCase(): array
    {
        return [
            'coin_value_input' => 0.05,
            'get_coin_by_value_output' => ['an existing coin'],
            'expected_output' => ['an existing coin']
        ];
    }
}
