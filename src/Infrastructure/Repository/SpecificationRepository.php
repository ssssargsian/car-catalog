<?php
namespace App\Infrastructure\Repository;

use App\Domain\Entity\Specification;
use App\Domain\Repository\SpecificationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specification[]    findAll()
 * @method Specification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SpecificationRepository extends ServiceEntityRepository implements SpecificationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specification::class);
    }

    public function add(Specification $specification, bool $flush = true): void
    {
        $this->getEntityManager()->persist($specification);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Specification $specification, bool $flush = true): void
    {
        $this->getEntityManager()->remove($specification);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
