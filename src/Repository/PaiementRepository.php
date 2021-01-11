<?php

namespace App\Repository;

use App\Entity\Paiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Paiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiement[]    findAll()
 * @method Paiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

    // /**
    //  * @return Paiement[] Returns an array of Paiement objects
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
    public function findOneBySomeField($value): ?Paiement
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findFraisNonRegles($inscription)
    {
        $em = $this->getEntityManager($inscription);
        $query = $em->createQuery(
            '
            select fr.designation, fr.montant, fr.id
            from App\Entity\Frais fr
            where fr.id not in 
                (
                select f.id
                from App\Entity\Frais f, App\Entity\Paiement p, App\Entity\Inscription i
                where f.id = p.frais and i.id = p.inscription and p.inscription = :inscription
                )
            '
        );
        return $query->setParameter('inscription', $inscription)->getResult();
    }

    public function NonRegles($inscription)
    {
        $em = $this->getEntityManager($inscription);
        $query = $em->createQuery(
            'select fr.designation, fr.montant, fr.id
            from App\Entity\Frais fr
            where fr.id not in 
                (select f.id
                from App\Entity\Frais f, App\Entity\Paiement p, App\Entity\Inscription i
                where f.id = p.frais and i.id = p.inscription and p.inscription = :inscription)'
        );
        return $query->setParameter('inscription', $inscription)->getResult();
    }

    public function findFraisRegles($inscription)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
            select f.designation, f.montant, f.id, p.created_at, p.montant_paye, p.montant_reste
                from App\Entity\Frais f, App\Entity\Paiement p, App\Entity\Inscription i
                where f.id = p.frais and i.id = p.inscription and i.id = :inscription
            '
        );
        return $query->setParameter('inscription', $inscription)->getResult();
    }
}
