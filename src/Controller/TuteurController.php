<?php

namespace App\Controller;

use App\Entity\Tuteur;
use App\Form\TuteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TuteurController extends AbstractController
{
    /**
     * @Route("/tuteur", name="tuteur")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tuteur = new Tuteur();
        $form = $this->createForm(TuteurType::class, $tuteur);
        if ($form->isSubmitted() and $form->isValid())
        {
            dd($tuteur);
            $entityManager->persist($tuteur);
            //$entityManager->flush();
        }
        return $this->render('tuteur/nouveau.html.twig', [
            'controller_name' => 'TuteurController',
            'form'=>$form->createView()
        ]);
    }
}
