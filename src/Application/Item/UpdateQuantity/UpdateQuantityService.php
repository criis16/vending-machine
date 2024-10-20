<?php

namespace App\Application\Item\UpdateQuantity;

use App\Application\Item\GetItemByName\GetItemByNameService;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemQuantity;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use InvalidArgumentException;

class UpdateQuantityService
{
    private ItemRepositoryInterface $repository;
    private GetItemByNameService $getItemByNameService;

    public function __construct(
        ItemRepositoryInterface $repository,
        GetItemByNameService $getItemByNameService
    ) {
        $this->repository = $repository;
        $this->getItemByNameService = $getItemByNameService;
    }

    /**
     * Updates the quantity of the item
     *
     * @param integer $quantity
     * @return boolean
     */
    public function execute(InsertItemRequest $request, int $quantity): bool
    {
        $items = $this->getItemByNameService->execute($request);

        if (empty($items)) {
            throw new InvalidArgumentException('The item with that name does not exist.');
        }

        $item = \reset($items);
        $itemId = new ItemId($item['id']);
        $itemQuantity = new ItemQuantity($quantity);
        return $this->repository->updateItemQuantity($itemId, $itemQuantity);
    }
}
