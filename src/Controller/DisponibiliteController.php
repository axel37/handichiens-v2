<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Entity\Famille;
use App\Repository\DisponibiliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{
    /**
     * Planning de la famille authentifiée
     * @return Response
     */
    #[Route('/mes-disponibilites', name: 'app_disponibilite_profil')]
    public function mesDispo(): Response
    {
        if ($this->isGranted('ROLE_FAMILLE') && $this->getUser() instanceof Famille) {
            return $this->renderPlanning($this->getUser());
        } else {
            throw new AccessDeniedHttpException("Vous devez être authentifié en tant que famille pour accéder à cette ressource.");
        }
    }

    /**
     * Disponibilités de toutes les familles
     * @param DisponibiliteRepository $dispoRepo
     * @return Response
     */
    #[Route('/disponibilite', name: 'app_disponibilite_index')]
    public function index(DisponibiliteRepository $dispoRepo): Response
    {
        if ($this->isGranted('ROLE_EDUCATEUR')) {

            $disponibilites = $dispoRepo->findAll();
            return $this->render('/disponibilite/index.html.twig', [
                'disponibilites' => $disponibilites
            ]);
        } else {
            throw new AccessDeniedHttpException("Vous devez être authentifié pour accéder à cette ressource.");
        }
    }

    /**
     * Détails d'une disponibilité (visualisation / modification / suppression ?)
     * @param Disponibilite $disponibilite
     * @return Response
     */
    #[Route('/disponibilite/{disponibilite}', name: 'app_disponibilite_details')]
    public function details(Disponibilite $disponibilite): Response
    {
        return $this->render('/disponibilite/details.html.twig', [
            'disponibilite' => $disponibilite
        ]);
    }


    /**
     * Disponibilités de la famille en paramètre dans l'url
     * @param Famille $famille
     * @return Response
     */
    #[Route('famille/{famille}/disponibilite/', name: 'app_disponibilite_famille')]
    public function parFamille(Famille $famille): Response
    {
        return $this->renderPlanning($famille);
    }


    /**
     * Affiche la liste des disponibilités de la famille passée en paramètre.
     *
     * <u>Note :</u>  Ceci n'est pas une route !
     * @param Famille $famille
     * @return Response
     */
    private function renderPlanning(Famille $famille): Response
    {
        return $this->render('/disponibilite/famille.html.twig', [
            'famille' => $famille,
        ]);
    }
}