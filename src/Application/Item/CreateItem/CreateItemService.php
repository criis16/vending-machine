<?php

namespace App\Application\Item\CreateItem;

use App\Domain\Item\Item;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use App\Domain\Item\ItemQuantity;
use App\Domain\Item\Repositories\ItemRepositoryInterface;

class CreateItemService
{
    private ItemRepositoryInterface $repository;

    public function __construct(
        ItemRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Saves a new item with the given name, price, and quantity.
     *
     * @param string $name
     * @param float $price
     * @param integer $quantity
     * @return boolean
     */
    public function execute(
        string $name,
        float $price,
        int $quantity
    ): bool {
        return $this->repository->saveItem(
            new Item(
                new ItemName($name),
                new ItemQuantity($quantity),
                new ItemPrice($price)
            )
        );
    }
}
