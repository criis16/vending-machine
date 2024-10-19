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

    private EntityManagerInterface $entityManager;
    private EntityRepository $entityRepository;
    private EntityCoinAdapter $entityCoinAdapter;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityCoinAdapter $entityCoinAdapter
    ) {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(EntityCoin::class);
        $this->entityCoinAdapter = $entityCoinAdapter;
    }

    public function getAllCoins(): array
    {
        return $this->applyEntityAdapter($this->entityRepository->findAll());
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
        $entityCoin = new EntityCoin();
        $entityCoin->setValue($coin->getCoinValue()->getValue());
        $entityCoin->setQuantity($coin->getCoinQuantity()->getValue());

        $this->entityManager->persist($entityCoin);
        $this->entityManager->flush();

        return \boolval($entityCoin->getId());
    }

    public function updateCoinQuantity(
        CoinId $coinId,
        CoinQuantity $coinQuantity
    ): bool {
        $entityCoin = $this->entityRepository->find($coinId->getValue());
        $entityCoin->setQuantity($coinQuantity->getValue());

        $this->entityManager->persist($entityCoin);
        $this->entityManager->flush();

        return \boolval($entityCoin->getId());
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
