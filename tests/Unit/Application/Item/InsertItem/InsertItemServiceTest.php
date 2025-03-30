<?php

namespace Tests\Unit\Application\Item\InsertItem;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\CreateItem\CreateItemService;
use App\Application\Item\InsertItem\InsertItemService;
use App\Application\Item\Exceptions\ItemNotSavedException;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;
use App\Application\Item\UpdateQuantity\UpdateQuantityService;

class InsertItemServiceTest extends TestCase
{
    private InsertItemService $sut;

    /** @var GetItemByNameService&MockObject */
    private GetItemByNameService $getItemByNameService;

    /** @var CreateItemService&MockObject */
    private CreateItemService $createItemService;

    /** @var UpdateQuantityService&MockObject */
    private UpdateQuantityService $updateQuantityService;



    protected function setUp(): void
    {
        $this->getItemByNameService = $this->createMock(GetItemByNameService::class);
        $this->createItemService = $this->createMock(CreateItemService::class);
        $this->updateQuantityService = $this->createMock(UpdateQuantityService::class);

        $this->sut = new InsertItemService(
            $this->getItemByNameService,
            $this->createItemService,
            $this->updateQuantityService
        );
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
            ->willReturn($itemNameValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($itemQuantityValue);
        $request->expects(self::once())
            ->method('getPrice')
            ->willReturn($itemPriceValue);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([]);

        $this->createItemService->expects(self::once())
            ->method('execute')
            ->with($itemNameValue, $itemPriceValue, $itemQuantityValue)
            ->willReturn(true);

        $this->sut->execute($request);
    }

    public function testExecuteInsertsItemThrowsItemNotSavedException(): void
    {
        $itemNameValue = 'an item name';
        $itemQuantityValue = 10;
        $itemPriceValue = 1.65;

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn($itemNameValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($itemQuantityValue);
        $request->expects(self::once())
            ->method('getPrice')
            ->willReturn($itemPriceValue);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([]);

        $this->createItemService->expects(self::once())
            ->method('execute')
            ->with($itemNameValue, $itemPriceValue, $itemQuantityValue)
            ->willReturn(false);

        $this->expectException(ItemNotSavedException::class);
        $this->expectExceptionMessage('The inserted item has not been saved');

        $this->sut->execute($request);
    }

    public function testExecuteUpdateItemCorrectly(): void
    {
        $itemNameValue = 'an item name';
        $currentItemQuantityValue = 10;
        $newItemQuantityValue = 10;
        $item = [
            'quantity' => $currentItemQuantityValue,
        ];

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn($itemNameValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($newItemQuantityValue);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([$item]);

        $this->updateQuantityService->expects(self::once())
            ->method('execute')
            ->with($request, $currentItemQuantityValue + $newItemQuantityValue)
            ->willReturn(true);

        $this->sut->execute($request);
    }

    public function testExecuteUpdateItemThrowsItemNotSavedException(): void
    {
        $itemNameValue = 'an item name';
        $currentItemQuantityValue = 10;
        $newItemQuantityValue = 10;
        $item = [
            'quantity' => $currentItemQuantityValue,
        ];

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $request->expects(self::once())
            ->method('getName')
            ->willReturn($itemNameValue);
        $request->expects(self::once())
            ->method('getQuantity')
            ->willReturn($newItemQuantityValue);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([$item]);

        $this->updateQuantityService->expects(self::once())
            ->method('execute')
            ->with($request, $currentItemQuantityValue + $newItemQuantityValue)
            ->willReturn(false);

        $this->expectException(ItemNotSavedException::class);
        $this->expectExceptionMessage('The inserted item has not been saved');

        $this->sut->execute($request);
    }
}
