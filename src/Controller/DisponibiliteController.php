<?php

namespace App\Controller;

use App\Entity\Famille;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{
    /**
     * - Famille : Disponibilités d'une famille (elle-même)
     * - Éducateur : Disponibilités de toutes les familles
     * @return Response
     */
    #[Route('/disponibilite', name: 'app_disponibilite')]
    public function index(): Response
    {
        // NON : "Une route qui affiche deux choses"
        if ($this->isGranted('ROLE_EDUCATEUR'))
        {
            // TODO : Vue : liste de toutes les disponibilités
            throw new NotFoundHttpException("Cette page n'a pas encore été implémentée ! \n(/disponibilite pour ROLE_EDUCATEUR : Liste des disponibilités de toutes les familles)");
        }
        elseif ($this->isGranted('ROLE_FAMILLE') && $this->getUser() instanceof Famille)
        {
            return $this->renderPlanning($this->getUser());
        }
        else
        {
            // TODO : Que faire ici ? S'agit-il d'une Response ou d'une erreur ?
            //  Il devrait de toute manière être impossible d'arriver ici (security.yaml restreint cette route aux utilisateurs authentifiés)
            throw new AccessDeniedHttpException("Vous devez être authentifié pour accéder à cette ressource.");
        }
    }

    public function parFamille(Famille $famille): Response
    {
       return $this->renderPlanning($famille);
    }


    /**
     * Affiche la liste des disponibilités d'une famille
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
