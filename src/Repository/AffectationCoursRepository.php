<?php

namespace App\Repository;

use App\Entity\AffectationCours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AffectationCours|null find($id, $lockMode = null, $lockVersion = null)
 * @method AffectationCours|null findOneBy(array $criteria, array $orderBy = null)
 * @method AffectationCours[]    findAll()
 * @method AffectationCours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationCoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AffectationCours::class);
    }

    // /**
    //  * @return AffectationCours[] Returns an array of AffectationCours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AffectationCours
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
