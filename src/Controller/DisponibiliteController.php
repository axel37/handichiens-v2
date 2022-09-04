<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Entity\Famille;
use App\Form\DisponibiliteType;
use App\Repository\DisponibiliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{
    /**
     * Planning de la famille authentifiée
     * @return Response
     */
    #[Route('/mes-disponibilites', name: 'app_mes_disponibilites')]
    public function mesDispo(): Response
    {
        if ($this->isGranted('ROLE_FAMILLE') && $this->getUser() instanceof Famille) {
            return $this->renderPlanning($this->getUser());
        } else {
            throw new AccessDeniedHttpException("Vous devez être authentifié en tant que famille pour accéder à cette ressource.");
        }
    }

    /**
     * Ajout d'une disponibilité par la famille authentifiée
     * @param Request $request
     * @param DisponibiliteRepository $dispoRepository
     * @return Response
     */
    #[Route('/mes-disponibilites/ajouter', name: 'app_mes_disponibilites_ajouter')]
    public function mesDispoAjouter(Request $request, DisponibiliteRepository $dispoRepository): Response
    {
        $nouvelleDispo = new Disponibilite();
        $nouvelleDispo->setFamille($this->getUser());

        $form = $this->createForm(DisponibiliteType::class, $nouvelleDispo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $nouvelleDispo = $form->getData();

            $dispoRepository->add($nouvelleDispo, true);

            return $this->redirectToRoute('app_mes_disponibilites');
        }

        return $this->render('disponibilite/ajouter.html.twig', [
            "form" => $form->createView(),
            'famille' => $this->getUser(),
        ]);
    }

    /**
     * Liste des disponibilités de toutes les familles
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
     * Liste des disponibilités d'une famille
     * @param Famille $famille
     * @return Response
     */
    #[Route('famille/{famille}/disponibilite', name: 'app_disponibilite_famille')]
    public function parFamille(Famille $famille): Response
    {
        return $this->renderPlanning($famille);
    }

    /**
     * Ajouter une disponibilité à une famille
     * @param Famille $famille
     * @return Response
     */
    #[Route('famille/{famille}/disponibilite/ajouter', name: 'app_disponibilite_famille_ajouter')]
    public function ajouterAFamille(Famille $famille, Request $request, DisponibiliteRepository $dispoRepository): Response
    {
        $nouvelleDispo = new Disponibilite();
        $form = $this->createForm(DisponibiliteType::class, $nouvelleDispo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $nouvelleDispo = $form->getData();
            $nouvelleDispo->setFamille($famille);

            $dispoRepository->add($nouvelleDispo, true);

            return $this->redirectToRoute('app_disponibilite_famille', ['famille' => $famille->getId()]);
        }

        return $this->render('disponibilite/ajouter.html.twig', [
            "form" => $form->createView(),
            'famille' => $famille,
        ]);
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
