<?php

namespace App\Application\Item\GetAllItems;

use App\Domain\Item\Repositories\ItemRepositoryInterface;

class GetAllItemsService
{
    private ItemRepositoryInterface $repository;

    public function __construct(
        ItemRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Returns all the items
     *
     * @return array
     */
    public function execute(): array
    {
        $items = $this->repository->getAllItems();
        $itemsToReturn = [];

        foreach ($items as $item) {
            $itemName = $item->getItemName();
            $itemQuantity = $item->getItemQuantity();
            $itemsToReturn[] = [
                'name' => $itemName->getValue(),
                'quantity' => $itemQuantity->getValue(),
                'price' => $item->getItemPrice()->getValue()
            ];
        }

        return $itemsToReturn;
    }
}
