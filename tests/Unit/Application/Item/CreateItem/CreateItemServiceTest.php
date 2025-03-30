<?php

namespace Tests\Unit\Application\Item\CreateItem;

use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use PHPUnit\Framework\TestCase;
use App\Domain\Item\ItemQuantity;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Item\CreateItem\CreateItemService;
use App\Domain\Item\Repositories\ItemRepositoryInterface;

class CreateItemServiceTest extends TestCase
{
    private CreateItemService $sut;

    /** @var ItemRepositoryInterface&MockObject */
    private ItemRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ItemRepositoryInterface::class);
        $this->sut = new CreateItemService(
            $this->repository
        );
    }

    public function testExecuteWorksCorrectly(): void
    {
        $itemNameValue = 'water';
        $itemPriceValue = 0.65;
        $itemQuantityValue = 10;

        $itemName = new ItemName($itemNameValue);
        $itemPrice = new ItemPrice($itemPriceValue);
        $itemQuantity = new ItemQuantity($itemQuantityValue);
        $item = new Item($itemName, $itemQuantity, $itemPrice);

        $this->repository->expects(self::once())
            ->method('saveItem')
            ->with($item)
            ->willReturn(true);

        $this->assertTrue(
            $this->sut->execute($itemNameValue, $itemPriceValue, $itemQuantityValue)
        );
    }
}
