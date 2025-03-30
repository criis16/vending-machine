<?php

namespace Tests\Unit\Application\Item\GetItemByName;

use App\Domain\Coin\CoinValue;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Application\Coin\GetCoinsBack\GetCoinsBackService;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\Exceptions\ItemsNotReturnException;
use App\Application\Item\GetItemByName\GetItemByNameService;
use App\Application\Item\UpdateQuantity\UpdateQuantityService;
use App\Application\Status\UpdateBalance\UpdateBalanceService;
use App\Application\Item\GetItemByName\SelectItemByNameService;
use App\Application\Status\GetBalance\GetCurrentBalanceService;

class SelectItemByNameServiceTest extends TestCase
{
    private SelectItemByNameService $sut;

    /** @var GetItemByNameService&MockObject */
    private GetItemByNameService $getItemByNameService;

    /** @var GetCurrentBalanceService&MockObject */
    private GetCurrentBalanceService $getCurrentBalanceService;

    /** @var UpdateQuantityService&MockObject */
    private UpdateQuantityService $updateQuantityService;

    /** @var UpdateBalanceService&MockObject */
    private UpdateBalanceService $updateBalanceService;

    /** @var GetCoinsBackService&MockObject */
    private GetCoinsBackService $getCoinsBackService;


    protected function setUp(): void
    {
        $this->getItemByNameService = $this->createMock(GetItemByNameService::class);
        $this->getCurrentBalanceService = $this->createMock(GetCurrentBalanceService::class);
        $this->updateQuantityService = $this->createMock(UpdateQuantityService::class);
        $this->updateBalanceService = $this->createMock(UpdateBalanceService::class);
        $this->getCoinsBackService = $this->createMock(GetCoinsBackService::class);

        $this->sut = new SelectItemByNameService(
            $this->getItemByNameService,
            $this->getCurrentBalanceService,
            $this->updateQuantityService,
            $this->updateBalanceService,
            $this->getCoinsBackService
        );
    }

    public function testExecuteThrowsItemsNotReturnExceptionWhenItemDoesNotExist(): void
    {
        $this->expectException(ItemsNotReturnException::class);
        $this->expectExceptionMessage('The item does not exist. Please contact the service team.');

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->with($request)
            ->willReturn([]);

        $this->sut->execute($request);
    }

    public function testExecuteThrowsItemsNotReturnExceptionWhenItemIsNotAvailable(): void
    {
        $this->expectException(ItemsNotReturnException::class);
        $this->expectExceptionMessage('The item is not available. Please contact the service.');

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->with($request)
            ->willReturn([
                [
                    'quantity' => 0
                ]
            ]);

        $this->sut->execute($request);
    }

    public function testExecuteThrowsItemsNotReturnExceptionWhenCurrentBalanceIsNotEnough(): void
    {
        $this->expectException(ItemsNotReturnException::class);
        $this->expectExceptionMessage('The balance is not enough. Please insert coins.');

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->with($request)
            ->willReturn([
                [
                    'quantity' => 10,
                    'price' => 1.65
                ]
            ]);

        $this->getCurrentBalanceService->expects(self::once())
            ->method('execute')
            ->willReturn(1.00);

        $this->sut->execute($request);
    }

    public function testExecuteWorksCorrectly(): void
    {
        $currentBalance = 1.00;
        $itemPrice = 0.65;
        $itemQuantity = 10;
        $coinsToReturn = [
            '0.05' => 1,
            '0.10' => 2,
            '0.25' => 0
        ];

        /** @var InsertItemRequest&MockObject */
        $request = $this->createMock(InsertItemRequest::class);

        $this->getItemByNameService->expects(self::once())
            ->method('execute')
            ->with($request)
            ->willReturn([
                [
                    'quantity' => $itemQuantity,
                    'price' => $itemPrice
                ]
            ]);

        $this->getCurrentBalanceService->expects(self::once())
            ->method('execute')
            ->willReturn($currentBalance);

        $this->getCoinsBackService->expects(self::once())
            ->method('execute')
            ->with($currentBalance - $itemPrice, CoinValue::ALLOWED_RETURN_COIN_VALUES)
            ->willReturn($coinsToReturn);

        $this->updateBalanceService->expects(self::exactly(2))
            ->method('execute')
            ->willReturnMap([
                [$currentBalance - $itemPrice],
                []
            ]);

        $this->updateQuantityService->expects(self::once())
            ->method('execute')
            ->with($request, $itemQuantity - 1);

        $this->assertEquals($coinsToReturn, $this->sut->execute($request));
    }
}
