<?php

namespace App\Infrastructure\Coin\Repositories;

use App\Application\Coin\Adapters\EntityCoinAdapter;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinId;
use App\Domain\Coin\CoinQuantity;
use App\Domain\Coin\CoinValue;
use App\Entity\Coin as EntityCoin;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Coin\Repositories\CoinRepositoryInterface;

class DoctrineRepository implements CoinRepositoryInterface
{
    private const VALUE_FIELD = 'value';

    private EntityCoin $entityCoin;
    private EntityManagerInterface $entityManager;
    private EntityRepository $entityRepository;
    private EntityCoinAdapter $entityCoinAdapter;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityCoinAdapter $entityCoinAdapter
    ) {
        $this->entityCoin = new EntityCoin();
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(EntityCoin::class);
        $this->entityCoinAdapter = $entityCoinAdapter;
    }

    public function getCoinByValue(CoinValue $coinValue): array
    {
        return $this->applyEntityAdapter(
            $this->entityRepository->findBy(
                [self::VALUE_FIELD => $coinValue->getValue()]
            )
        );
    }

    public function saveCoin(Coin $coin): bool
    {
        $this->entityCoin->setValue($coin->getCoinValue()->getValue());
        $this->entityCoin->setQuantity($coin->getCoinQuantity()->getValue());

        $this->entityManager->persist($this->entityCoin);
        $this->entityManager->flush();

        return \boolval($this->entityCoin->getId());
    }

    public function updateCoinQuantity(
        CoinId $coinId,
        CoinQuantity $coinQuantity
    ): bool {
        $this->entityCoin = $this->entityRepository->find($coinId->getValue());
        $this->entityCoin->setQuantity(
            $this->entityCoin->getQuantity() + $coinQuantity->getValue()
        );

        $this->entityManager->persist($this->entityCoin);
        $this->entityManager->flush();

        return \boolval($this->entityCoin->getId());
    }

    private function applyEntityAdapter(array $coins): array
    {
        return \array_map(
            function (EntityCoin $entityCoin) {
                return $this->entityCoinAdapter->adapt($entityCoin);
            },
            $coins
        );
    }
}
