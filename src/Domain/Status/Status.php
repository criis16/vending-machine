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
     * Get the value of statusId
     *
     * @return StatusId
     */
    public function getStatusId(): StatusId
    {
        return $this->statusId;
    }

    /**
     * Set the value of statusId
     *
     * @param StatusId $statusId
     * @return void
     */
    public function setStatusId(StatusId $statusId): void
    {
        $this->statusId = $statusId;
    }

    /**
     * Get the value of statusBalance
     *
     * @return StatusBalance
     */
    public function getStatusBalance(): StatusBalance
    {
        return $this->statusBalance;
    }

    /**
     * Set the value of statusBalance
     *
     * @param StatusBalance $statusBalance
     * @return void
     */
    public function setStatusBalance(StatusBalance $statusBalance): void
    {
        $this->statusBalance = $statusBalance;
    }
}
