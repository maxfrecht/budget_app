<?php

namespace App\Repository;

use App\Entity\PaiementCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaiementCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaiementCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaiementCategory[]    findAll()
 * @method PaiementCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaiementCategory::class);
    }

    // /**
    //  * @return PaiementCategory[] Returns an array of PaiementCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaiementCategory
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
