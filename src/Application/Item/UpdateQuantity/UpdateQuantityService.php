<?php

namespace App\Application\Item\UpdateQuantity;

use App\Domain\Item\ItemId;
use InvalidArgumentException;
use App\Domain\Item\ItemQuantity;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use App\Application\Item\GetItemByName\GetItemByNameService;

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
     * @param InsertItemRequest $request
     * @param integer $quantity
     * @throws InvalidArgumentException
     * @return boolean
     */
    public function execute(InsertItemRequest $request, int $quantity): bool
    {
        $items = $this->getItemByNameService->execute($request);

        if (empty($items)) {
            throw new InvalidArgumentException(
                'The requested item with does not exist. Please contact the service team.'
            );
        }

        $item = \reset($items);
        $itemId = new ItemId($item['id']);
        $itemQuantity = new ItemQuantity($quantity);
        return $this->repository->updateItemQuantity($itemId, $itemQuantity);
    }
}
