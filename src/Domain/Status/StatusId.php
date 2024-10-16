<?php

namespace App\Domain\Status;

class StatusId
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Return the status id value
     *
     * @return integer
     */
    public function getValue(): int
    {
        return $this->id;
    }
}
