<?php

namespace App\Domain\Item;

class ItemId
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Return the item id value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->id;
    }
}
