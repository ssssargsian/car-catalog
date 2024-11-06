<?php
namespace App\Infrastructure\Repository;

use App\Domain\Entity\Brand;
use App\Domain\Repository\BrandRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class BrandRepository extends ServiceEntityRepository implements BrandRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    public function add(Brand $brand, bool $flush = true): void
    {
        $this->getEntityManager()->persist($brand);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Brand $brand, bool $flush = true): void
    {
        $this->getEntityManager()->remove($brand);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
