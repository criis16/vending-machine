<?php

namespace App\Infrastructure\Item\Repositories;

use App\Domain\Item\Item;
use App\Domain\Item\ItemId;
use App\Domain\Item\ItemName;
use App\Domain\Item\ItemQuantity;
use App\Entity\Item as EntityItem;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Application\Item\Adapters\EntityItemAdapter;
use App\Domain\Item\Repositories\ItemRepositoryInterface;

class DoctrineRepository implements ItemRepositoryInterface
{
    private const NAME_FIELD = 'name';

    private EntityManagerInterface $entityManager;
    private EntityRepository $entityRepository;
    private EntityItemAdapter $entityItemAdapter;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityItemAdapter $entityItemAdapter
    ) {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(EntityItem::class);
        $this->entityItemAdapter = $entityItemAdapter;
    }

    public function getAllItems(): array
    {
        return $this->applyEntityAdapter($this->entityRepository->findAll());
    }

    public function getItemByName(ItemName $itemName): array
    {
        return $this->applyEntityAdapter(
            $this->entityRepository->findBy(
                [self::NAME_FIELD => $itemName->getValue()]
            )
        );
    }

    public function saveItem(Item $item): bool
    {
        $entityItem = new EntityItem();
        $entityItem->setName($item->getItemName()->getValue());
        $entityItem->setQuantity($item->getItemQuantity()->getValue());
        $entityItem->setPrice($item->getItemPrice()->getValue());

        $this->entityManager->persist($entityItem);
        $this->entityManager->flush();

        return \boolval($entityItem->getId());
    }

    public function updateItemQuantity(
        ItemId $itemId,
        ItemQuantity $itemQuantity
    ): bool {
        $entityItem = $this->entityRepository->find($itemId->getValue());
        $entityItem->setQuantity($itemQuantity->getValue());

        $this->entityManager->persist($entityItem);
        $this->entityManager->flush();

        return \boolval($entityItem->getId());
    }

    private function applyEntityAdapter(array $items): array
    {
        return \array_map(
            function (EntityItem $entityItem) {
                return $this->entityItemAdapter->adapt($entityItem);
            },
            $items
        );
    }
}
