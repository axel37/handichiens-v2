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
    // Le repository est utilisé pour récupérer les familles correspondant aux dates
    public function __construct(FamilleRepository $familleRepository)
    {
        $this->familleRepository = $familleRepository;
    }

    // Ajout des champs statiques (chien, debut, fin) et des eventListeners
    // Les eventListeners ajoutent le champ famille en fonction des dates choisies
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Restriction des années min/max
        $today = new DateTimeImmutable('now');
        $anneeMin = $today->format('Y');
        $anneeMax = $today->add(new DateInterval('P1Y'))->format('Y');

        // Ajout des champs de formulaire "statiques"
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
            ])
            ->add('fin', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Fin de la disponibilité',
                'with_seconds' => false,
                'years' => range($anneeMin, $anneeMax),
            ])
            // Le champ famille est ajouté par les event listeners
            ->add('Enregistrer', SubmitType::class);

        // EVENT LISTENER : Construction du formulaire
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $affectation = $event->getData();

            $debut = null;
            $fin = null;

            if (isset($affectation)) {
                $debut = $affectation->getDebut();
                $fin = $affectation->getFin();
            }

            // Ajout du champ "famille"
            $this->ajouterChampFamille($event->getForm(), $debut, $fin);
        });

        // EVENT LISTENER : Changement de la date "début"
        $builder->get('debut')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            // Récupération de la date de début
            $debut = $event->getForm()->getData();

            // Récupération de la date de fin, si elle existe
            $form = $event->getForm()->getParent();
            $fin = null;
            if (isset($form)) {
                $fin = $form->get('fin')->getData();
            }

            // Ajout du champ "famille"
            $this->ajouterChampFamille($event->getForm()->getParent(), $debut, $fin);
        });

        // EVENT LISTENER : Changement de la date "fin"
        $builder->get('fin')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            // Récupération de la date de fin
            $fin = $event->getForm()->getData();

            // Récupération de la date de début, si elle existe
            $form = $event->getForm()->getParent();
            $debut = null;
            if (isset($form)) {
                $debut = $form->get('debut')->getData();
            }

            // Ajout du champ "famille"
            $this->ajouterChampFamille($event->getForm()->getParent(), $debut, $fin);
        });
    }

    // Ajout du champ Famille en fonction d'une date de début et d'une date de fin
    private function ajouterChampFamille(FormInterface $form, ?DateTimeImmutable $debut, ?DateTimeImmutable $fin)
    {
        // Récupération des familles disponibles pour accueillir un chien sur le créneau début -> fin
        $familles = $this->familleRepository->findByDisponibilite($debut, $fin);

        // Ajout du champ
        $form->add('famille', EntityType::class, [
            'class' => Famille::class,
            'placeholder' => 'Choisir une famille',
            'choices' => $familles
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
