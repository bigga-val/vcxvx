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
            select f.designation, f.montant, f.id, p.created_at, p.montant_paye, p.montant_reste, p.token
                from App\Entity\Frais f, App\Entity\Paiement p, App\Entity\Inscription i
                where f.id = p.frais and i.id = p.inscription and i.id = :inscription
            '
        );
        return $query->setParameter('inscription', $inscription)->getResult();
    }

    public function findElevesOrdreFrais($frais, $classe)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
                select e.nom_complet, p.montant_paye, p.montant_reste
                from App\Entity\Eleve e, App\Entity\Paiement p, App\Entity\Inscription i, App\Entity\Frais f
                where p.inscription = i.id 
                    and p.frais = f.id
                    and e.id = i.eleve
                    and f.id = :frais
                    and i.classe = :classe   
            '
        );
        return $query->setParameter('frais', $frais)->setParameter('classe', $classe)->getResult();
    }

    public function findHistoriquePaiement($date)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
                select e.nom_complet, f.designation frais, f.montant montant, p.montant_paye, p.montant_reste, p.id, c.designation classe, o.designation option, substring(p.created_at, 1, 10) date  
                FROM App\Entity\Paiement p, App\Entity\Frais f, App\Entity\Eleve e, App\Entity\Inscription i,
                    App\Entity\Classe c, App\Entity\Option o
                where substring(p.created_at, 1, 10) = :date
                    and p.is_active = 1
                    and f.id = p.frais
                    and i.id = p.inscription
                    and e.id = i.eleve
                    and o.id = c.options
                    and c.id = i.classe
            '
        );
        return $query->setParameter('date', $date)->getResult();
    }

    public function findHistoriquePaiements()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            '
                select e.nom_complet, f.designation frais, f.montant montant, p.montant_paye, p.montant_reste, 
                    p.id, c.designation classe, o.designation option, substring(p.created_at, 1, 10) date  
                FROM App\Entity\Paiement p, App\Entity\Frais f, App\Entity\Eleve e, App\Entity\Inscription i,
                    App\Entity\Classe c, App\Entity\Option o
                where p.is_active = :active
                    and f.id = p.frais
                    and i.id = p.inscription
                    and e.id = i.eleve
                    and o.id = c.options
                    and c.id = i.classe
                ORDER BY date asc
            '
        );
        return $query->setParameter('active', true)->getResult();
    }
}

