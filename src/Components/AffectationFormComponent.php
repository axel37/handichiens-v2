<?php

namespace App\Components;

use App\Entity\Affectation;
use App\Entity\Famille;
use App\Form\AffectationType;
use App\Repository\FamilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public ?Affectation $affectation = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(AffectationType::class, $this->affectation);
    }
}