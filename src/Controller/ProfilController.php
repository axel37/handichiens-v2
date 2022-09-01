<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this -> entityManager = $entityManager;
    }

    // Récupère les données de l'utilisateur
    #[Route('/mon-profil', name: 'app_profil')]
    public function index(): Response
    {
        $profil = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'utilisateur' => $profil,
        ]);
    }

    // Modifie les données de l'utilisateur
    #[Route('/mon-profil/modifier', name: 'app_profil_modifier')]
    public function modifier(Request $request): Response {

        $profil = $this->getUser();

        $form = $this->createForm(UtilisateurType::class, $profil);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $profil = $form->getData();

            $this->entityManager->persist($profil);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
