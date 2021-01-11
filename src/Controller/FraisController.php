<?php

namespace App\Controller;

use App\Entity\Frais;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FraisController extends AbstractController
{
    /**
     * @Route("/frais", name="frais")
     */
    public function index(): Response
    {
        $form = $this->createForm(Frais::class);
        return $this->render('frais/index.html.twig', [
            'controller_name' => 'FraisController',
            'form'=>$form->createView()
        ]);
    }
}
