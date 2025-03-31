<?php

namespace App\Application\Status\CreateStatus;

use App\Domain\Status\Status;
use App\Domain\Status\StatusBalance;
use App\Domain\Status\Repositories\StatusRepositoryInterface;

class CreateStatusService
{
    private StatusRepositoryInterface $repository;

    public function __construct(
        StatusRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }


    public function execute(
        float $balance
    ): bool {
        return $this->repository->saveStatus(
            new Status(
                new StatusBalance($balance)
            )
        );
    }
}
