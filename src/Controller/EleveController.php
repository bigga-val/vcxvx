<?php

namespace App\Controller;

use App\Entity\AnneeScolaire;
use App\Entity\Categorie;
use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Tuteur;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class EleveController extends AbstractController
{
    /**
     * @Route("/eleve/id", name="eleve")
     */
    public function index(): Response
    {
        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
        ]);
    }

    /**
     * @Route("/affectation_eleve/{id}", name="affectation_eleve")
     */
    public function affecter_eleve($id, EntityManagerInterface $entityManager, Request $request)
    {
        $eleve = $this->getDoctrine()->getRepository(Inscription::class)
            ->findOneBy(['token'=>$id]);
        if($request->isMethod('post'))
        {
            $data = $request->request->all();
            if($this->isCsrfTokenValid('affectation', $data['_token']))
            {
                $classe = $this->getDoctrine()->getRepository(Classe::class)
                    ->find($data['classe']);
                $eleve->setClasse($classe);
                $entityManager->persist($eleve);
                $entityManager->flush();
            }
        }
        $classes = $this->getDoctrine()->getRepository(Classe::class)
            ->findAll();
        $etats = $this->getDoctrine()->getRepository(Etat::class)
            ->findAll();

        return $this->render('eleve/affectation.html.twig', [
            'eleve'=>$eleve,
            'classes'=>$classes,
            'etats'=>$etats
        ]);
    }

    /**
     * @Route("/changer_etat", name="changer_etat")
     */
    public function changer_etat(EntityManagerInterface $entityManager, Request $request)
    {
        if($request->isMethod('POST'))
        {
            $data = $request->request->all();
            //dd($data);
            if($this->isCsrfTokenValid('etat', $data['_token']))
            {
                $eleve = $this->getDoctrine()->getRepository(Eleve::class)
                    ->find($data['eleve']);
                $inscription = $this->getDoctrine()->getRepository(Inscription::class)
                    ->findOneBy(['eleve'=>$eleve]);
                $etat = $this->getDoctrine()->getRepository(Etat::class)
                    ->find($data['etat']);
                $eleve->setEtat($etat);
                $entityManager->persist($eleve);
                //dd($inscription);
                $entityManager->flush();
                return $this->redirectToRoute('affectation_eleve', ['id'=> $inscription->getId()]);
            }
        }
    }

    /**
     * @Route("eleves_non_actifs", name="eleves_non_actifs")
     */
    public function eleves_non_actifs()
    {
        $inscriptions = $this->getDoctrine()->getRepository(Inscription::class)
            ->findElevesNonActifs();
        return $this->render('/eleve/eleves_non_actifs.html.twig', [
            'inscriptions'=>$inscriptions
        ]);
    }

    /**
     * @Route("eleves_disponibles", name="eleves_disponibles")
     */
    public function eleves_disponibles()
    {
        $eleves = $this->getDoctrine()->getRepository(Eleve::class)
            ->findAll();
        return $this->render('eleve/eleves_disponibles.html.twig', [
            'eleves'=>$eleves
        ]);
    }

    /**
     * @Route("profil_eleve/{id}", name="profil_eleve")
     */
    public function profil_eleve($id, Request $request, EntityManagerInterface $entityManager)
    {
        $session = new Session();
        $eleve = $this->getDoctrine()->getRepository(Eleve::class)
            ->find($id);
        $tuteurs = $this->getDoctrine()->getRepository(Tuteur::class)
            ->findAll();
        $etats = $this->getDoctrine()->getRepository(Etat::class)
            ->findBy(['is_active'=>true], []);
        $categories = $this->getDoctrine()->getRepository(Categorie::class)
            ->findBy(['is_active'=>true], []);
        $classes = $this->getDoctrine()->getRepository(Classe::class)
            ->findBy(['is_active'=> true], []);
        $annee = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->find($session->get('id_annee'));
        if($request->isMethod('post')){
            $data = $request->request->all();
            if($this->isCsrfTokenValid('place', $data['_token']))
            {
                $inscription = new Inscription();
                $inscription->setToken($data['_token']);
                $inscription->setAnneeScolaire($annee);
                $inscription->setEleve($eleve);
                $inscription->setCreatedBy($this->getUser());
                $inscription->setClasse($this->getDoctrine()->getRepository(Classe::class)
                    ->find($data['classe']));
                $inscription->setCreatedAt(new \DateTime('now'));
                $entityManager->persist($inscription);
                $entityManager->flush();
            }
        }
        //dd($eleve);
        return $this->render('eleve/profil_eleve.html.twig', [
            'eleve'=>$eleve,
            'classes'=>$classes,
            'tuteurs'=>$tuteurs,
            'categories'=>$categories,
            'etats'=>$etats
        ]);
    }

    /**
     * @Route("modifier_eleve", name="modifier_eleve")
     */
    public function modifier_eleve(Request $request,EntityManagerInterface $entityManager)
    {

        if($request->isMethod('post'))
        {

            $data = $request->request->all();
            if($this->isCsrfTokenValid('modifier', $data['_token']))
            {
                $eleve = $this->getDoctrine()->getRepository(Eleve::class)
                    ->find($data['eleve']);
                if($data['date_naissance'] != "")
                {
                    $eleve->setDateNaissance($data['date_naissance']);
                }
                if(isset($data['categorie']) or !empty($data['categorie']) and $data['categorie'] > 0)
                {
                    $eleve->setCategorie($this->getDoctrine()->getRepository(Categorie::class)
                        ->find($data['categorie']));
                }

                if(isset($data['etat']) or !empty($data['etat']) and $data['etat'] > 0)
                {
                    $eleve->setEtat($this->getDoctrine()->getRepository(Etat::class)
                        ->find($data['etat'])
                    );
                }
                if(isset($data['tuteur']) or !empty($data['tuteur']) and $data['tuteur'] > 0)
                {
                    $eleve->setTuteur($this->getDoctrine()->getRepository(Tuteur::class)
                        ->find($data['tuteur'])
                    );
                }
                $eleve->setNomComplet($data['nom_complet']);
                $eleve->setLieuNaissance($data['lieu_naissance']);
                $eleve->setAdresse($data['adresse']);
                $entityManager->persist($eleve);
                $entityManager->flush();
                return $this->redirectToRoute('profil_eleve', ['id'=>$data['eleve']]);
            }
        }
    }
}
