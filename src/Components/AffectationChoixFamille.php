<?php

namespace App\Components;

use App\Form\Type\AffectationChoixFamilleType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('affectation_choix_famille')]
class AffectationChoixFamille extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?\DateTimeImmutable $debut;

    #[LiveProp(writable: true)]
    public ?\DateTimeImmutable $fin;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AffectationChoixFamilleType::class);
    }

    public function getFamilles()
    {
        // Liste des familles ayant une disponibilité pour début et fin
    }
}