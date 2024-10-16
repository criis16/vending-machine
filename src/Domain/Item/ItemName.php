<?php

namespace App\Domain\Item;

class ItemName
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return the item name
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->name;
    }
}
