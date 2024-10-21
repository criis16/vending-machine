<?php

namespace Tests\Unit\Application\Item\UpdateQuantity;

use App\Domain\Item\ItemId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;
use App\Application\Item\UpdateQuantity\UpdateQuantityService;

class UpdateQuantityServiceTest extends TestCase
{
    private UpdateQuantityService $sut;

    /** @var ItemRepositoryInterface&MockObject */
    private ItemRepositoryInterface $repository;

    /** @var GetItemByNameService&MockObject */
    private GetItemByNameService $getItemByNameService;

    protected function setUp(): void
    {
        $this->getItemByNameService = $this->createMock(GetItemByNameService::class);
        $this->repository = $this->createMock(ItemRepositoryInterface::class);
        $this->sut = new UpdateQuantityService(
            $this->repository,
            $this->getItemByNameService
        );
    }

    public function testExecuteThrowsExceptionWhenNoStatusBalanceFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The requested item with does not exist. Please contact the service team.');

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([]);

        $this->sut->execute($request, 10);
    }

    public function testExecuteWorksCorrectly(): void
    {
        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);
        $itemIdValue = 23;
        $quantity = 10;

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->willReturn([
                [
                    'id' => $itemIdValue
                ]
            ]);
        $itemId = new ItemId($itemIdValue);
        $itemQuantity = new ItemQuantity($quantity);

        $this->repository->expects(self::once())
            ->method('updateItemQuantity')
            ->with($itemId, $itemQuantity)
            ->willReturn(true);

        $this->assertTrue($this->sut->execute($request, $quantity));
    }
}
