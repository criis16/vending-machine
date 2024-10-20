<?php

namespace App\Application\Status\GetStatus;

use App\Domain\Status\Status;
use App\Application\Status\Adapters\StatusAdapter;
use App\Domain\Status\Repositories\StatusRepositoryInterface;

class GetStatusService
{
    private StatusRepositoryInterface $repository;
    private StatusAdapter $adapter;

    public function __construct(
        StatusRepositoryInterface $repository,
        StatusAdapter $adapter
    ) {
        $this->repository = $repository;
        $this->adapter = $adapter;
    }

    /**
     * Returns every status object in the database
     *
     * @return array
     */
    public function execute(): array
    {
        return \array_map(
            function (Status $status) {
                return $this->adapter->adapt($status);
            },
            $this->repository->getStatus()
        );
    }
}
