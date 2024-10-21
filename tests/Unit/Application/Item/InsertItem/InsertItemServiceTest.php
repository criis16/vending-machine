<?php

namespace Tests\Unit\Application\Item\InsertItem;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use InvalidArgumentException;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\InsertItem\InsertItemService;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;

class InsertItemServiceTest extends TestCase
{
    private InsertItemService $sut;

    /** @var ItemRepositoryInterface&MockObject */
    private ItemRepositoryInterface $repository;

    /** @var GetItemByNameService&MockObject */
    private GetItemByNameService $getItemByNameService;

    protected function setUp(): void
    {
        $this->getItemByNameService = $this->createMock(GetItemByNameService::class);
        $this->repository = $this->createMock(ItemRepositoryInterface::class);
        $this->sut = new InsertItemService(
            $this->repository,
            $this->getItemByNameService
        );
    }

    public function testExecuteThrowsExceptionWhenNoItemFound(): void
    {
        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The item name cannot be empty.');
        $this->sut->execute($request);
    }

    public function testExecuteInsertsItemCorrectly(): void
    {
        $itemNameValue = 'an item name';
        $itemQuantityValue = 10;
        $itemPriceValue = 1.65;

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn($itemNameValue, $itemNameValue, $itemNameValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($itemQuantityValue, $itemQuantityValue);
        $request->expects(self::once())
            ->method('getPrice')
            ->willReturn($itemPriceValue, $itemPriceValue);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([]);

        $itemName = new ItemName($itemNameValue);
        $itemPrice = new ItemPrice($itemPriceValue);
        $itemQuantity = new ItemQuantity($itemQuantityValue);
        $item = new Item($itemName, $itemQuantity, $itemPrice);

        $this->repository->expects(self::once())
            ->method('saveItem')
            ->with($item)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request));
    }

    public function testExecuteUpdateItemQuantityCorrectly(): void
    {
        $itemIdValue = 23;
        $itemNameValue = 'Water';
        $insertItemQuantityValue = 55;
        $currentItemQuantityValue = 10;
        $updatedQuantityValue = $currentItemQuantityValue + $insertItemQuantityValue;

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn($itemNameValue, $itemNameValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($insertItemQuantityValue, $insertItemQuantityValue);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([
                [
                    'id' => $itemIdValue,
                    'quantity' => $currentItemQuantityValue,
                ]
            ]);

        $itemId = new ItemId($itemIdValue);
        $itemQuantity = new ItemQuantity($updatedQuantityValue);

        $this->repository->expects(self::once())
            ->method('updateItemQuantity')
            ->with($itemId, $itemQuantity)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request));
    }
}
