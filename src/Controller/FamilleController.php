<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Repository\FamilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FamilleController extends AbstractController
{
    #[Route('/famille', name: 'app_famille_index')]
    public function index(FamilleRepository $familleRepository): Response
    {
        $familles = $familleRepository->findAll();
        return $this->render('famille/index.html.twig', [
            'familles' => $familles,
        ]);
    }

    #[Route('/famille/{famille}', name: 'app_famille_details')]
    public function details(Famille $famille): Response
    {
        return $this->render('utilisateur/details.html.twig', [
            'utilisateur' => $famille,
        ]);
    }
}
