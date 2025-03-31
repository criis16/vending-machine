<?php

namespace App\Application\Item\InsertItem;

use InvalidArgumentException;
use App\Infrastructure\Item\Repositories\InsertItemRequest;

class InsertItemsService
{
    private InsertItemRequest $insertItemRequest;
    private InsertItemService $insertItemService;

    public function __construct(
        InsertItemRequest $insertItemRequest,
        InsertItemService $insertItemService,
    ) {
        $this->insertItemRequest = $insertItemRequest;
        $this->insertItemService = $insertItemService;
    }

    /**
     * Inserts multiple items into the system.
     *
     * @param array $items
     * @throws InvalidArgumentException
     * @return void
     */
    public function execute(
        array $items
    ): void {
        foreach ($items as $itemName => $itemQuantity) {
            if (empty($itemName) || empty($itemQuantity)) {
                throw new InvalidArgumentException(
                    'Something is wrong with the items. Name:' . $itemName . ' Quantity:' . $itemQuantity
                );
            }

            $this->insertItemRequest->setName($itemName);
            $this->insertItemRequest->setQuantity($itemQuantity);
            $this->insertItemService->execute($this->insertItemRequest);
        }
    }
}
