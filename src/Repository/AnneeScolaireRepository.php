<?php

namespace App\Repository;

use App\Entity\AnneeScolaire;
use App\Entity\EtatAnnee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method AnneeScolaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnneeScolaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnneeScolaire[]    findAll()
 * @method AnneeScolaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneeScolaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnneeScolaire::class);
    }

    // /**
    //  * @return AnneeScolaire[] Returns an array of AnneeScolaire objects
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
    public function findOneBySomeField($value): ?AnneeScolaire
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * fonction permettant de definir l'année en cours pour laquelle on veut effectuer les actions
     */
    public function find_current_year(){
        $current = $this->getDoctrine()->getRepository(EtatAnnee::class)
            ->findOneBy(['designation'=>'en cours'], []);
        $year = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->findOneBy(['etat'=>$current->getId()]);
        $session = new Session();
        $session->set('annee_courante', $year->getDesignation());
        $session->set('id_annee', $year->getId());
    }
}
