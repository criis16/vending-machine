<?php

namespace App\Application\Item\GetAllItems;

use App\Application\Item\Adapters\ItemAdapter;
use App\Domain\Item\Item;
use App\Domain\Item\Repositories\ItemRepositoryInterface;

class GetAllItemsService
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
     * Returns all the items
     *
     * @return array
     */
    public function execute(): array
    {

        return \array_map(
            function (Item $item) {
                return $this->adapter->adapt($item);
            },
            $this->repository->getAllItems()
        );
    }
}
