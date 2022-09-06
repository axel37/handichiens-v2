<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Chien;
use App\Entity\Famille;
use App\Repository\FamilleRepository;
use DateInterval;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    public function __construct(FamilleRepository $familleRepository)
    {
        $this->familleRepository = $familleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $familles = $this->familleRepository->findAll();

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
//            ->add('famille', EntityType::class, [
//                'class' => Famille::class,
//                'placeholder' => 'Aucune famille sélectionnée',
//                'choices' => $familles
//            ])
            ->add('Enregistrer', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $affectation = $event->getData();

            $debut = null;
            $fin = null;

            if (isset($affectation)) {
                $debut = $affectation->getDebut();
                $fin = $affectation->getFin();
            }

            $this->ajouterChampFamille($event->getForm(), $debut, $fin);
        });


        $builder->get('debut')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $debut = $event->getForm()->getData();

            $form = $event->getForm()->getParent();
            $fin = null;

            if (isset($form)) {
                $fin = $form->get('fin')->getData();
            }

            $this->ajouterChampFamille($event->getForm()->getParent(), $debut, $fin);
        });

        $builder->get('fin')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $fin = $event->getForm()->getData();

            $form = $event->getForm()->getParent();
            $debut = null;

            if (isset($form)) {
                $debut = $form->get('debut')->getData();
            }

            $this->ajouterChampFamille($event->getForm()->getParent(), $debut, $fin);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }

    private function ajouterChampFamille(FormInterface $form, ?DateTimeImmutable $debut, ?DateTimeImmutable $fin)
    {
        $familles = $this->familleRepository->findByDisponibilite($debut, $fin);

        $form->add('famille', EntityType::class, [
            'class' => Famille::class,
            'placeholder' => 'Aucune famille sélectionnée',
            'choices' => $familles
        ]);
    }
}
