<?php

namespace App\Domain\Status;

class Status
{
    private StatusId $statusId;
    private StatusBalance $statusBalance;

    public function __construct(
        StatusId $statusId,
        StatusBalance $statusBalance
    ) {
        $this->statusId = $statusId;
        $this->statusBalance = $statusBalance;
    }

    /**
     * Get the domain object of statusId
     *
     * @return StatusId
     */
    public function getStatusId(): StatusId
    {
        return $this->statusId;
    }

    /**
     * Get the domain object of statusBalance
     *
     * @return StatusBalance
     */
    public function getStatusBalance(): StatusBalance
    {
        return $this->statusBalance;
    }
}
