<?php

namespace App\Form;

use App\Entity\Disponibilite;
use DateInterval;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $today = new DateTimeImmutable('now');
        $dateDebut = new DateTimeImmutable('tomorrow 10am');
        $dateFin = new DateTimeImmutable('+2 days 6pm');
        $anneeMin = $today->format('Y');
        $anneeMax = $today->add(new DateInterval('P1Y'))->format('Y');

        $builder
            ->add('debut', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Début de la disponibilité',
                'with_seconds' => false,
                'years' => range($anneeMin, $anneeMax),
                'data' => $dateDebut,
            ])
            ->add('fin', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Fin de la disponibilité',
                'with_seconds' => false,
                'years' => range($anneeMin, $anneeMax),
                'data' => $dateFin,
            ])
            ->add('libelle')
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
