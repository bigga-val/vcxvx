<?php

namespace App\Controller;

use App\Entity\Frais;
use App\Entity\Inscription;
use App\Entity\Paiement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
