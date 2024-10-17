<?php

namespace App\Domain\Status;

class Status
{
    private StatusId $statusId;
    private StatusBalance $statusBalance;

    public function __construct(
        StatusBalance $statusBalance
    ) {
        $this->statusBalance = $statusBalance;
    }

    /**
     * Sets the status id
     *
     * @param StatusId $statusId
     * @return void
     */
    public function setStatusId(StatusId $statusId): void
    {
        $this->statusId = $statusId;
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
