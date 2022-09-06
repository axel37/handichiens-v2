<?php

namespace App\Components;

use App\Entity\Affectation;
use App\Form\AffectationType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Composant affichant le formulaire d'affectation
#[AsLiveComponent('affectation_form')]
class AffectationFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'data')]
    // L'Affectation passée au formulaire
    public ?Affectation $affectation = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AffectationType::class, $this->affectation);
    }
}