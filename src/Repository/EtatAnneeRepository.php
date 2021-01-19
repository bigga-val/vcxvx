<?php

namespace App\Repository;

use App\Entity\EtatAnnee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatAnnee|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatAnnee|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatAnnee[]    findAll()
 * @method EtatAnnee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatAnneeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatAnnee::class);
    }

    // /**
    //  * @return EtatAnnee[] Returns an array of EtatAnnee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtatAnnee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
