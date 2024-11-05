<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Stringable;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Brand      get(Stringable|string $uuid, bool $withSoftDeleted = false, bool $cached = true)
 */
final class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    public function add(Brand $brand): void
    {
        $this->getEntityManager()->persist($brand);
    }

    public function remove(Brand $brand): void
    {
        $this->getEntityManager()->remove($brand);
    }
}
