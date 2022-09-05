<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Chien;
use App\Entity\Famille;
use DateInterval;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $today = new DateTimeImmutable('now');
        $dateDebut = new DateTimeImmutable('tomorrow 10am');
        $dateFin = new DateTimeImmutable('+2 days 6pm');
        $anneeMin = $today->format('Y');
        $anneeMax = $today->add(new DateInterval('P1Y'))->format('Y');

        $builder
            ->add('chien', EntityType::class, [
                'class' => Chien::class,
                'placeholder' => 'Aucun chien sélectionné',
                'autocomplete' => true,
            ])
            ->add('famille', EntityType::class, [
                'class' => Famille::class,
                'placeholder' => 'Aucune famille sélectionnée',
            ])
            ->add('debut', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Début de la disponibilité',
                'with_seconds' => false,
                'years' => range($anneeMin, $anneeMax),
//                'data' => $dateDebut,
            ])
            ->add('fin', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Fin de la disponibilité',
                'with_seconds' => false,
                'years' => range($anneeMin, $anneeMax),
//                'data' => $dateFin,
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
