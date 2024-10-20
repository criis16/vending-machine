<?php

namespace App\Domain\Status\Repositories;

use App\Domain\Status\Status;
use App\Domain\Status\StatusId;
use App\Domain\Status\StatusBalance;

interface StatusRepositoryInterface
{
    /**
     * Returns the status
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
