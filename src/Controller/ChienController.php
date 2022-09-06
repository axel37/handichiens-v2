<?php

namespace App\Controller;

use App\Entity\Chien;
use App\Form\ChienType;
use App\Repository\ChienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChienController extends AbstractController
{
    #[Route('/chien', name: 'app_chien_index')]
    public function index(ChienRepository $chienRepository): Response
    {
        $chiens = $chienRepository->findAll();

        return $this->render('chien/index.html.twig', [
            'chiens' => $chiens,
        ]);
    }

    #[Route('/chien/{chien}', name: 'app_chien_details', requirements: ['chien' => '\d+'])]
    public function details(Chien $chien): Response
    {
        return $this->render('chien/details.html.twig', [
            'chien' => $chien
        ]);
    }

    #[Route('/chien/ajouter', name: 'app_chien_ajouter')]
    public function ajouter(Request $request, ChienRepository $chienRepository): Response
    {
        $nouveauChien = new Chien();
        $form = $this->createForm(ChienType::class, $nouveauChien);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouveauChien = $form->getData();
            $chienRepository->add($nouveauChien, true);

            return $this->redirectToRoute('app_chien_details', ['chien' => $nouveauChien->getId()]);
        }

        return $this->render('chien/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/chien/{chien}/modifier', name: 'app_chien_modifier')]
    public function modifier(Chien $chien, Request $request, ChienRepository $chienRepository, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ChienType::class, $chien);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chien = $form->getData();

            $manager->persist($chien);
            $manager->flush();

            return $this->redirectToRoute('app_chien_details', ['chien' => $chien->getId()]);
        }

        return $this->render('chien/modifier.html.twig', [
            'form' => $form->createView(),
            'chien' => $chien
        ]);
    }

    #[Route('/chien/{chien}/supprimer', name: 'app_chien_supprimer')]
    public function supprimer(Chien $chien, ChienRepository $chienRepository): Response
    {

        $chien->anonymiser();

        $chienRepository->add($chien, true);

        return $this->redirectToRoute('app_chien_index');
    }
}
