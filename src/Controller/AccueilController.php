<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * **Page d'accueil** / Tableau de bord
     *
     * En fonction de l'utilisateur, afficher :
     * - Une invitation à s'authentifier
     * - Le tableau de bord d'une famille
     * - Le tableau de bord d'un éducateur
     */
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        // Choix de la vue en fonction du rôle de l'utilisateur

        $vue = 'accueil/visiteur.html.twig';

        if ($this->isGranted('ROLE_ADMIN')) {
            $vue = 'accueil/administrateur.html.twig';
        } elseif ($this->isGranted('ROLE_EDUCATEUR')) {
            $vue = 'accueil/educateur.html.twig';
        } elseif ($this->isGranted('ROLE_FAMILLE')) {
            $vue = 'accueil/famille.html.twig';
        }

        return $this->render($vue);
    }
}
