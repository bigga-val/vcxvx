<?php

namespace App\Controller;

use App\Entity\AnneeScolaire;
use App\Entity\Categorie;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Tuteur;
use App\Controller\AnneeScolaireController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use function mysql_xdevapi\getSession;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscriptions", name="inscriptions")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();
        //dd($session->get('annee_courante'));
        //$inscriptions = $this->getDoctrine()->getRepository(Inscription::class)
        //  ->findBy(['eleve.etat_id'=>1]);
        $inscripts = $this->getDoctrine()->getRepository(Inscription::class)
            ->findElevesActifs($session->get('id_annee'));

        //dd($inscripts);
        $user = $this->getUser();
        $annee_en_cours = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->findOneBy(['etat'=>'en cours'], []);
        $session = new Session();
        //dd($session->get('annee_courante'));
        return $this->render('inscription/login.html.twig', [
            'controller_name' => 'InscriptionController',
            'inscriptions' => $inscripts,
            'annee_en_cours'=>$annee_en_cours
        ]);
    }

    /**
     * @Route("/nouvelle_inscription", name="nouvelle_inscription")
     */
    public function inscrire(Request $request, EntityManagerInterface $entityManager)
    {
        if($request->isMethod('POST'))
        {
            $session = new Session();
            $data = $request->request->all();
            if($this->isCsrfTokenValid('inscription', $data['_token']))
            {
                $eleve = new Eleve();
                $inscription = new Inscription();
                $eleve->setCategorie($this->getDoctrine()->getRepository(Categorie::class)
                    ->find($data['categorie']));
                $eleve->setEtat($this->getDoctrine()->getRepository(Etat::class)
                    ->find($data['etat']));
                $eleve->setTuteur($this->getDoctrine()->getRepository(Tuteur::class)
                    ->find($data['tuteur']));
                $eleve->setAdresse($data['adresse']);
                $eleve->setDateNaissance(new \DateTime($data['date_naissance']));
                $eleve->setLieuNaissance($data['lieu_naissance']);
                $eleve->setNomComplet($data['nom_complet']);
                $entityManager->persist($eleve);

                $inscription->setCreatedAt(new \DateTime('now'));
                $inscription->setClasse($this->getDoctrine()->getRepository(Classe::class)
                    ->find($data['classe']));
                $inscription->setEleve($eleve);
                $inscription->setAnneeScolaire($this->getDoctrine()->getRepository(AnneeScolaire::class)
                    ->find($session->get('id_annee')));
                $inscription->setToken($data['_token']);
                $entityManager->persist($inscription);

                $entityManager->flush();
            }
        }
        $annees = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->findAll();
        $classes = $this->getDoctrine()->getRepository(Classe::class)
            ->findAll();
        $tuteurs = $this->getDoctrine()->getRepository(Tuteur::class)
            ->findBy([], ['id'=>'asc']);
        $categories = $this->getDoctrine()->getRepository(Categorie::class)
            ->findAll();
        $etats = $this->getDoctrine()->getRepository(Etat::class)
            ->findAll();
        return $this->render('inscription/index.html.twig', [
           'annees'=>$annees,
           'categories'=>$categories,
           'etats'=>$etats,
           'classes'=>$classes,
            'tuteurs'=>$tuteurs
        ]);
    }
}
