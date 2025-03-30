<?php

namespace Tests\Unit\Application\Item\InsertItem;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\InsertItem\InsertItemService;
use App\Application\Item\InsertItem\InsertItemsService;
use App\Infrastructure\Item\Repositories\InsertItemRequest;

class InsertItemsServiceTest extends TestCase
{
    private InsertItemsService $sut;

    /** @var InsertItemRequest&MockObject */
    private InsertItemRequest $insertItemRequest;

    /** @var InsertItemService&MockObject */
    private InsertItemService $insertItemService;

    protected function setUp(): void
    {
        $this->insertItemRequest = $this->createMock(InsertItemRequest::class);
        $this->insertItemService = $this->createMock(InsertItemService::class);

        $this->sut = new InsertItemsService(
            $this->insertItemRequest,
            $this->insertItemService
        );
    }

    public function testExecuteInsertsItemCorrectly(): void
    {
        $inputItems = [
            'item name' => 10
        ];

        $this->insertItemService->expects(self::once())
            ->method('execute')
            ->willReturnMap([
                [$this->insertItemRequest]
            ]);

        $this->sut->execute($inputItems);
    }

    public function testExecuteInsertsItemThrowsInvalidArgumentException(): void
    {
        $inputItems = [
            'item name' => null
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Something is wrong with the items. Name:item name Quantity:');

        $this->insertItemService->expects(self::never())
            ->method('execute')
            ->willReturnMap([
                [$this->insertItemRequest]
            ]);

        $this->sut->execute($inputItems);
    }
}
