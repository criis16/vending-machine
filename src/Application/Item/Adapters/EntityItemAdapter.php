<?php

namespace App\Application\Item\Adapters;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemPrice;
use App\Domain\Item\ItemQuantity;
use App\Entity\Item as EntityItem;

class EntityItemAdapter
{
    /**
     * Adapts an Item Entity object into a Domain object
     *
     * @param EntityItem $entityItem
     * @return Item
     */
    public function adapt(
        EntityItem $entityItem
    ): Item {
        $item = new Item(
            new ItemName($entityItem->getName()),
            new ItemQuantity($entityItem->getQuantity()),
            new ItemPrice($entityItem->getPrice())
        );

        $item->setItemId(
            new ItemId($entityItem->getId())
        );

        return $item;
    }
}
