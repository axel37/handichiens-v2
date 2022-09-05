<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Repository\AffectationRepository;
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
    public function ajouter(Request $request, AffectationRepository $affectationRepository): Response
    {
        $nouvelleAffectation = new Affectation();
        $form = $this->createForm(AffectationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouvelleAffectation = $form->getData();

            $affectationRepository->add($nouvelleAffectation, true);

            // TODO : Rediriger vers la route précédente plutôt que simplement la route index
            $this->addFlash('success', 'Le chien a bien été affecté.');
            return $this->redirectToRoute('app_affectation_index');
        }

        return $this->render('affectation/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
