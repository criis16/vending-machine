<?php

namespace App\Domain\Status\Repositories;

use App\Domain\Status\Status;
use App\Domain\Status\StatusBalance;
use App\Domain\Status\StatusId;

interface StatusRepositoryInterface
{
    /**
     * Returns the status register
     *
     * @return array
     */
    public function getStatus(): array;

    /**
     * Saves the given status
     *
     * @param Status $status
     * @return boolean
     */
    public function saveStatus(Status $status): bool;

    /**
     * Update the given status balance
     *
     * @param StatusId $statusId
     * @param StatusBalance $statusBalance
     * @return boolean
     */
    public function updateStatusBalance(
        StatusId $statusId,
        StatusBalance $statusBalance
    ): bool;
}
