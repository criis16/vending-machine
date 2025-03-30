<?php

namespace Tests\Unit\Application\Item\GetItemByName;

use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\Adapters\ItemAdapter;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;

class GetItemByNameServiceTest extends TestCase
{
    private const ITEM_NAME = 'an item name';

    private GetItemByNameService $sut;

    /** @var ItemRepositoryInterface&MockObject */
    private ItemRepositoryInterface $repository;

    /** @var ItemAdapter&MockObject */
    private ItemAdapter $adapter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ItemRepositoryInterface::class);
        $this->adapter = $this->createMock(ItemAdapter::class);

        $this->sut = new GetItemByNameService(
            $this->repository,
            $this->adapter
        );
    }

    /**
     * @dataProvider getItemByNameProvider
     */
    public function testExecute(
        array $getItemByNameRepositoryOutput,
        array $adapterOutput,
        array $expectedOutput
    ): void {
        $itemNameValue = self::ITEM_NAME;

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn($itemNameValue);

        $itemName = new ItemName($itemNameValue);

        $this->repository->expects(self::once())
            ->method('getItemByName')
            ->with($itemName)
            ->willReturn($getItemByNameRepositoryOutput);

        $this->adapter->expects(self::exactly(\count($getItemByNameRepositoryOutput)))
            ->method('adapt')
            ->willReturn($adapterOutput);

        $this->assertEquals($expectedOutput, $this->sut->execute($request));
    }

    public static function getItemByNameProvider(): array
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
            'get_item_by_name_repository_output' => [],
            'adapter_output' => [],
            'expected_output' => []
        ];
    }

    private static function simpleCase(): array
    {
        $item = new Item(
            new ItemName(self::ITEM_NAME),
            new ItemQuantity(10),
            new ItemPrice(1.65)
        );

        $adaptedItem = ['an adapted item'];

        return [
            'get_item_by_name_repository_output' => [
                $item
            ],
            'adapter_output' => $adaptedItem,
            'expected_output' => [$adaptedItem]
        ];
    }

    private static function multipleCase(): array
    {
        $item = new Item(
            new ItemName(self::ITEM_NAME),
            new ItemQuantity(10),
            new ItemPrice(1.65)
        );

        $adaptedItem = ['an adapted item'];

        return [
            'get_item_by_name_repository_output' => [
                $item,
                $item
            ],
            'adapter_output' => $adaptedItem,
            'expected_output' => [$adaptedItem, $adaptedItem]
        ];
    }
}
