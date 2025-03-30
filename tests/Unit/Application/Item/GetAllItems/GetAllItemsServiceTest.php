<?php

namespace Tests\Unit\Application\Item\GetAllItems;

use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\Adapters\ItemAdapter;
use App\Application\Item\GetAllItems\GetAllItemsService;
use App\Domain\Item\Repositories\ItemRepositoryInterface;

class GetAllItemsServiceTest extends TestCase
{
    private GetAllItemsService $sut;

    /** @var ItemRepositoryInterface&MockObject */
    private ItemRepositoryInterface $repository;

    /** @var ItemAdapter&MockObject */
    private ItemAdapter $adapter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ItemRepositoryInterface::class);
        $this->adapter = $this->createMock(ItemAdapter::class);

        $this->sut = new GetAllItemsService(
            $this->repository,
            $this->adapter
        );
    }

    /**
     * @dataProvider getAllItemsProvider
     */
    public function testExecute(
        array $getAllItemsRepositoryOutput,
        array $adapterOutput,
        array $expectedResult
    ): void {
        $this->repository->expects(self::once())
            ->method('getAllItems')
            ->willReturn($getAllItemsRepositoryOutput);
        $this->adapter->expects(self::exactly(\count($getAllItemsRepositoryOutput)))
            ->method('adapt')
            ->willReturn($adapterOutput);

        $this->assertEquals($expectedResult, $this->sut->execute());
    }

    public static function getAllItemsProvider(): array
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
            'get_all_items_repository_output' => [],
            'adapter_output' => [],
            'expected_output' => []
        ];
    }

    private static function simpleCase(): array
    {
        $item = new Item(
            new ItemName('water'),
            new ItemQuantity(10),
            new ItemPrice(1.65)
        );

        $adaptedItem = ['an adapted item'];

        return [
            'get_all_items_repository_output' => [
                $item
            ],
            'adapter_output' => $adaptedItem,
            'expected_output' => [$adaptedItem]
        ];
    }

    private static function multipleCase(): array
    {
        $item = new Item(
            new ItemName('water'),
            new ItemQuantity(10),
            new ItemPrice(1.65)
        );

        $adaptedItem = ['an adapted item'];

        return [
            'get_all_items_repository_output' => [
                $item,
                $item
            ],
            'adapter_output' => $adaptedItem,
            'expected_output' => [$adaptedItem, $adaptedItem]
        ];
    }
}
