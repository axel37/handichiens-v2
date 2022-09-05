<?php

namespace App\Components;

use App\Repository\FamilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('affectation_choix_famille')]
class AffectationChoixFamille extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?\DateTimeImmutable $debut = null;
    #[LiveProp(writable: true)]
    public ?\DateTimeImmutable $fin = null;

    public function __construct(FamilleRepository $familleRepository)
    {
        $this->familleRepository = $familleRepository;
    }

    public function getFamilles(): array
    {
        // TODO : Temporaire, devrait être géré dans le Repository
        if (!isset($this->debut) && !isset($this->fin))
        {
            return [];
        }

        return $this->familleRepository->findByDisponibilite($this->debut, $this->fin);
    }
}