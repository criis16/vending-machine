<?php

namespace App\Infrastructure\Status\Repositories;

use App\Application\Status\Adapters\EntityStatusAdapter;
use App\Domain\Status\Status;
use App\Domain\Status\StatusBalance;
use App\Domain\Status\Repositories\StatusRepositoryInterface;
use App\Domain\Status\StatusId;
use App\Entity\Status as EntityStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineRepository implements StatusRepositoryInterface
{
    private EntityStatus $entityStatus;
    private EntityManagerInterface $entityManager;
    private EntityRepository $entityRepository;
    private EntityStatusAdapter $entityStatusAdapter;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityStatusAdapter $entityStatusAdapter
    ) {
        $this->entityStatus = new EntityStatus();
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(EntityStatus::class);
        $this->entityStatusAdapter = $entityStatusAdapter;
    }

    public function getStatus(): array
    {
        return $this->applyEntityAdapter($this->entityRepository->findAll());
    }

    public function saveStatus(Status $status): bool
    {
        $this->entityStatus->setBalance($status->getStatusBalance()->getValue());

        $this->entityManager->persist($this->entityStatus);
        $this->entityManager->flush();

        return \boolval($this->entityStatus->getId());
    }

    public function updateStatusBalance(
        StatusId $statusId,
        StatusBalance $statusBalance
    ): bool {
        $this->entityStatus = $this->entityRepository->find($statusId->getValue());
        $this->entityStatus->setBalance($statusBalance->getValue());

        $this->entityManager->persist($this->entityStatus);
        $this->entityManager->flush();

        return \boolval($this->entityStatus->getId());
    }

    private function applyEntityAdapter(array $status): array
    {
        return \array_map(
            function (EntityStatus $entityStatus) {
                return $this->entityStatusAdapter->adapt($entityStatus);
            },
            $status
        );
    }
}
