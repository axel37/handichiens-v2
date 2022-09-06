<?php

namespace App\Form;

use App\Entity\Chien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ChienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $today = new \DateTimeImmutable('today');
        // 30 ans en arrière
        $minDate = $today->sub(new \DateInterval('P30Y'));

        $builder
            ->add('nom')
            ->add('dateNaissance', DateType::class, [
                'input' => 'datetime_immutable',
                'data' => $today,
                'years' => range($minDate->format('Y'), $today->format('Y')),
            ])
            ->add('race')
            ->add('photo', VichImageType::class, [
                'required' => false
            ])
            ->add('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chien::class,
        ]);
    }
}
