<?php

namespace App\Application\Status\Adapters;

use App\Domain\Status\Status;

class StatusAdapter
{
    /**
     * Adapts a Status domain object into a an array
     *
     * @param Status $status
     * @return array
     */
    public function adapt(
        Status $status
    ): array {
        return [
            'id' => $status->getStatusId()->getValue(),
            'balance' => $status->getStatusBalance()->getValue()
        ];
    }
}
