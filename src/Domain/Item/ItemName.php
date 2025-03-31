<?php

namespace App\Domain\Item;

use InvalidArgumentException;

class ItemName
{
    public const WATER_ITEM_NAME = 'Water';
    public const JUICE_ITEM_NAME = 'Juice';
    public const SODA_ITEM_NAME = 'Soda';

    public const ALLOWED_ITEM_NAMES = [
        self::WATER_ITEM_NAME,
        self::JUICE_ITEM_NAME,
        self::SODA_ITEM_NAME
    ];

    private string $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('The item name cannot be empty.');
        }
        $this->validate($name);
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

    /**
     * Validate the item name
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return void
     */
    private function validate(string $value): void
    {
        if (!\in_array(ucfirst($value), self::ALLOWED_ITEM_NAMES)) {
            throw new InvalidArgumentException(
                'Invalid item name ' . $value . '. Allowed item names are: ' . \implode(', ', self::ALLOWED_ITEM_NAMES)
            );
        }
    }
}
