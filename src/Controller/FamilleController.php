<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Form\UtilisateurType;
use App\Repository\FamilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class FamilleController extends AbstractController
{
    /**
     * Liste de toutes les familles
     * @param FamilleRepository $familleRepository
     * @return Response
     */
    #[Route('/famille', name: 'app_famille_index')]
    public function index(FamilleRepository $familleRepository): Response
    {
        $familles = $familleRepository->findAll();
        return $this->render('famille/index.html.twig', [
            'familles' => $familles,
        ]);
    }

    /**
     * Détails d'une famille (similaire à la vue "mon-profil"
     * @param Famille $famille
     * @return Response
     */
    #[Route('/famille/{famille}', name: 'app_famille_details', requirements: ['famille' => '\d+'])]
    public function details(Famille $famille): Response
    {
        return $this->render('utilisateur/details.html.twig', [
            'utilisateur' => $famille,
        ]);
    }

//    /famille/{famille}/disponibilite = app_disponibilite_famille dans DisponibiliteController

    /**
     * Ajout d'une famille (par un éducateur ou un administrateur)
     * @return Response
     */
    #[Route('/famille/ajouter', name: 'app_famille_ajouter')]
    public function ajouter(Request $request, FamilleRepository $familleRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var Famille $nouvelleFamille */
        $nouvelleFamille = new Famille();

        // TODO : Gérer le mot de passe des familles créées par un éducateur / admin
        $nouvelleFamille->setPassword($passwordHasher->hashPassword($nouvelleFamille, 'test'));

        $form = $this->createForm(UtilisateurType::class, $nouvelleFamille);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var Famille $nouvelleFamille */
            $nouvelleFamille = $form->getData();

            $familleRepository->add($nouvelleFamille, true);

            return $this->redirectToRoute('app_famille_details', ['famille' => $nouvelleFamille->getId()]);
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
