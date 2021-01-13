<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Frais;
use App\Entity\Inscription;
use App\Entity\Paiement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class PaiementController extends AbstractController
{
    /**
     * @Route("/payement/{inscription}", name="paiement")
     * @param $inscription
     * @return Response
     */
    public function index($inscription, Request $request, EntityManagerInterface $entityManager): Response
    {
        $eleve = $this->getDoctrine()->getRepository(Inscription::class)
            ->find($inscription);
        if ($request->isMethod('POST'))
        {
            $data = $request->request->all();
            if($this->isCsrfTokenValid('paiement', $data['_token']))
            {
                $paiement = new Paiement();
                $paiement->setMontantPaye($data['montant_paye']);
                $paiement->setMontantReste($data['montant_reste']);
                $paiement->setFrais($this->getDoctrine()->getRepository(Frais::class)
                    ->find($data['frais']));
                $paiement->setInscription($eleve);
                $paiement->setCreatedAt(new \DateTime('now'));
                $entityManager->persist($paiement);
                $entityManager->flush();
            }
        }
        //dd($eleve);
        $frais_non_regles = $this->getDoctrine()->getRepository(Paiement::class)
            ->NonRegles($inscription);
        //dd($frais_non_regles)
        $frais_regles = $this->getDoctrine()->getRepository(Paiement::class)
            ->findFraisRegles($inscription);
        //dd($frais_regles);
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
            'eleve'=>$eleve,
            'frais'=>$frais_non_regles,
            'ordre'=>$frais_regles
        ]);
    }

    /**
     * @Route("eleves_en_ordre", name="eleves_en_ordre")
     */
    public function afficher_eleves()
    {
        $liste_frais = $this->getDoctrine()->getRepository(Frais::class)
            ->findBy(['is_active'=>true]);
        $liste_classes = $this->getDoctrine()->getRepository(Classe::class)
            ->findBy(['is_active'=>true]);
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)
            ->findBy(['is_active'=>true]);
        return $this->render('paiement/eleves_en_ordre.html.twig', [
            'liste_frais'=>$liste_frais,
            'liste_classes'=>$liste_classes,
            'paiements'=>$paiements
        ]);
    }

    /**
     * @Route("trier_eleves", name="trier_eleves")
     *
     */
    public function trier_eleves(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $frais = $data['frais'];
            $classe = $data['classe'];

            $eleves = $this->getDoctrine()->getRepository(Paiement::class)
                ->findElevesOrdreFrais($frais, $classe);
//            dd($eleves);
            return new JsonResponse($eleves);
        }
    }

    /**
     * @Route("historique_paiement", name="historique_paiement")
     */
    public function historique_paiement(Request $request)
    {
        $liste_classes = $this->getDoctrine()->getRepository(Classe::class)
            ->findBy(['is_active'=>true]);
        $liste_frais = $this->getDoctrine()->getRepository(Frais::class)
            ->findBy(['is_active'=>true]);
        $historique = $this->getDoctrine()->getRepository(Paiement::class)
            ->findHistoriquePaiements();
        if($request->isXmlHttpRequest())
        {
            $data = $request->request->all();
            $paiement = $this->getDoctrine()->getRepository(Paiement::class)
                ->findHistoriquePaiement($data['date']);
            return new JsonResponse($paiement);
        }

        return $this->render('paiement/historique_paiement.html.twig', [
            'liste_classes'=>$liste_classes,
            'liste_frais'=>$liste_frais,
            'paiements'=>$historique
        ]);
    }
}
