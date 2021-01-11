<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Eleve;
use App\Entity\Etat;
use App\Entity\Inscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        if($request->isMethod('post'))
        {
            $data = $request->request->all();
            if($this->isCsrfTokenValid('affectation', $data['_token']))
            {
                $eleve = $this->getDoctrine()->getRepository(Inscription::class)
                    ->find($id);
                $classe = $this->getDoctrine()->getRepository(Classe::class)
                    ->find($data['classe']);
                $eleve->setClasse($classe);
                $entityManager->persist($eleve);
                $entityManager->flush();
            }
        }

        $eleve = $this->getDoctrine()->getRepository(Inscription::class)
            ->find($id);
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
}
