<?php

namespace App\Application\Status\Adapters;

use App\Domain\Status\Status;
use App\Domain\Status\StatusBalance;
use App\Domain\Status\StatusId;
use App\Entity\Status as EntityStatus;

class EntityStatusAdapter
{
    /**
     * Adapts a Status Entity object into a Domain object
     *
     * @param EntityStatus $entityStatus
     * @return Status
     */
    public function adapt(
        EntityStatus $entityStatus
    ): Status {
        $status = new Status(
            new StatusBalance($entityStatus->getBalance())
        );

        $status->setStatusId(
            new StatusId($entityStatus->getId())
        );

        return $status;
    }
}
