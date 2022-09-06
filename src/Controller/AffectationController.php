<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Repository\AffectationRepository;
use App\Repository\ChienRepository;
use App\Repository\FamilleRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function ajouter(Request $request, AffectationRepository $affectationRepository, ChienRepository $chienRepository, FamilleRepository $familleRepository): Response
    {
        // Récupération des paramètres de l'url / GET : debut, fin et chien
        // Nous renseignons des dates par défaut si aucune n'est fournies (pour éviter 01/01/20XX) dans les deux champs)
        $dateDebut = new DateTimeImmutable($request->query->get('debut')) ?? new DateTimeImmutable('tomorrow 10am');
        $dateFin = new DateTimeImmutable($request->query->get('fin')) ?? new DateTimeImmutable('+2 days 6pm');
        $chien = $chienRepository->findOneById($request->query->get('chien'));
        $famille = $familleRepository->findOneById($request->query->get('famille'));

        // TODO : La famille n'est pas sélectionnée si une date est aussi passée en paramètre

        // Création de l'affectation
        $nouvelleAffectation = new Affectation();
        $nouvelleAffectation->setDebut($dateDebut);
        $nouvelleAffectation->setFin($dateFin);
        $nouvelleAffectation->setChien($chien);
        $nouvelleAffectation->setFamille($famille);

        $form = $this->createForm(AffectationType::class, $nouvelleAffectation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouvelleAffectation = $form->getData();

            $affectationRepository->add($nouvelleAffectation, true);

            // TODO : Rediriger vers la route précédente plutôt que simplement la route index
            $this->addFlash('success', 'Le chien a bien été affecté.');
            return $this->redirectToRoute('app_affectation_index');
        }

        return $this->render('affectation/ajouter.html.twig', [
            'form' => $form->createView(),
            'nouvelleAffectation' => $nouvelleAffectation
        ]);
    }

    #[Route('/affectation/{affectation}', name: 'app_affectation_details')]
    public function details(Affectation $affectation): Response
    {
        return $this->render('affectation/details.html.twig', [
            'affectation' => $affectation
        ]);
    }

    #[Route('/affectation/{affectation}/supprimer',  name: 'app_affectation_supprimer')]
    public function supprimer(Affectation $affectation, AffectationRepository $affectationRepository): Response
    {
        if ($affectation->isConfirme())
        {
            // TODO : Notifier la famille
        }
        $affectationRepository->remove($affectation, true);
        $this->addFlash('success', 'L\'affectation a été supprimée');

        return $this->redirectToRoute('app_affectation_index');
    }
}
