<?php

namespace App\Controller;

use App\Entity\AnneeScolaire;
use App\Entity\Etat;
use App\Entity\EtatAnnee;
use App\Repository\AnneeScolaireRepository;
use App\Repository\EtatAnneeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AnneeScolaireController extends AbstractController
{
    /**
     * @Route("/annee/scolaire", name="annee_scolaire")
     */
    public function index(): Response
    {
        $annees = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->findAll();
        return $this->render('annee_scolaire/index.html.twig', [
            'controller_name' => 'AnneeScolaireController',
            'annees'=>$annees
        ]);
    }

    /**
     * @Route("update_year/{id}", name="update_year")
     */
    public function update_current_year($id, Request $request, EntityManagerInterface $entityManager)
    {
        $year = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->find($id);
        $etats = $this->getDoctrine()->getRepository(EtatAnnee::class)
            ->findBy(['is_active'=> true]);
        if($request->isMethod('post'))
        {
            $data = $request->request->all();
            if($this->isCsrfTokenValid('annee', $data['_token']))
            {
                $year->setDesignation($data['designation']);
                $year->setEtat($this->getDoctrine()->getRepository(EtatAnnee::class)
                    ->find($data['etat']));
                $entityManager->persist($year);
                $entityManager->flush();
                if($year->getEtat()->getDesignation() == "en cours")
                {
                    $session = new Session();
                    $session->set('annee_courante', $year->getDesignation());
                    $session->set('id_annee', $year->getId());
                    //dd($session->get('annee_courante'));
                }

                return $this->redirectToRoute('annee_scolaire');
            }
        }
        return $this->render('annee_scolaire/edit_year.html.twig',[
            'annee'=>$year,
            'etats'=>$etats
        ]);
    }

    /**
     * @Route("current_year", name="current_year")
     */
    public function getCurrentYear()
    {
        $etat = $this->getDoctrine()->getRepository(EtatAnnee::class)
            ->findOneBy(['designation'=>'en cours'], []);
        $year = $this->getDoctrine()->getRepository(AnneeScolaire::class)
            ->findOneBy(['etat'=>$etat->getId()]);
        $session = new Session();
        $session->set('annee_courante', $year->getDesignation());
        $session->set('id_annee', $year->getId());
        return $this->redirectToRoute("inscriptions");
    }

}
