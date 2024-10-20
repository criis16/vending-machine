<?php

namespace App\Application\Item\Adapters;

use App\Domain\Item\Item;

class ItemAdapter
{
    /**
     * Adapts an Item domain object into a an array
     *
     * @param Item $entityItem
     * @return array
     */
    public function adapt(
        Item $item
    ): array {
        return [
            'id' => $item->getItemId()->getValue(),
            'name' => $item->getItemName()->getValue(),
            'quantity' => $item->getItemQuantity()->getValue(),
            'price' => $item->getItemPrice()->getValue()
        ];
    }
}
