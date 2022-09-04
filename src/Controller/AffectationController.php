<?php

namespace App\Controller;

use App\Repository\AffectationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AffectationController extends AbstractController
{
    #[Route('/affectation', name: 'app_affectation_index')]
    public function index(AffectationRepository $affectationRepository): Response
    {
        $affectations = $affectationRepository->findAll();

        return $this->render('affectation/index.html.twig', [
            'affectations' => $affectations
        ]);
    }

    #[Route('/affectation/ajouter', name: 'app_affectation_ajouter')]
    public function ajouter(): Response
    {
        // Todo
    }
}
