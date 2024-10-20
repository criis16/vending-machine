<?php

namespace App\Application\Item\GetItemByName;

use App\Application\Item\Adapters\ItemAdapter;
use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use App\Domain\Item\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Item\Repositories\InsertItemRequest;
use InvalidArgumentException;

class GetItemByNameService
{
    private ItemRepositoryInterface $repository;
    private ItemAdapter $adapter;

    public function __construct(
        ItemRepositoryInterface $repository,
        ItemAdapter $adapter
    ) {
        $this->repository = $repository;
        $this->adapter = $adapter;
    }

    /**
     * Returns the item with the given name
     *
     * @param InsertItemRequest $request
     * @return array
     */
    public function execute(InsertItemRequest $request): array
    {
        $name = $request->getName();

        if (empty($name)) {
            throw new InvalidArgumentException('The item name must be a valid value');
        }

        $itemName = new ItemName($name);
        $items = $this->repository->getItemByName($itemName);

        if (empty($items)) {
            throw new InvalidArgumentException(
                'The requested item does not exist. Please contact the service team.'
            );
        }

        return \array_map(
            function (Item $item) {
                return $this->adapter->adapt($item);
            },
            $items
        );
    }
}
