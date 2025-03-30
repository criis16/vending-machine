<?php

namespace App\Domain\Item;

use InvalidArgumentException;

class ItemName
{
    public const WATER_ITEM_NAME = 'Water';
    public const JUICE_ITEM_NAME = 'Juice';
    public const SODA_ITEM_NAME = 'Soda';

    private string $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('The item name cannot be empty.');
        }

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
