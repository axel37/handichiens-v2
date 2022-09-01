<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On crée un formulaire avec les champs de base d'un utilisateur.
        $builder
            //->add('roles')
            //->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone');

    // On va vérifier si l'utilisateur est une famille
    // Si oui, on rajoute les champs "Adresse", "Ville" et "CodePostal"
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            $roles = $user->getRoles();

            // Vérification si l'utilisateur est une "Famille"
            $famille = false;
            for( $i = 0; $i < count($roles); $i++) {
                if ($roles[$i] === "ROLE_FAMILLE") {
                    $famille = true;
                }
            }
            if ( $famille === true ) {
                $form
                    ->add('adresse',TextType::class, [
                        'required' => true,
                        'constraints' => new Length(['min' => 3, 'max' => 255])
                    ])
                    ->add('ville',TextType::class, [
                        'required' => true,
                        'constraints' => new Length(['min' => 2, 'max' => 125])
                    ])
                    ->add('codePostal',TextType::class, [
                        'required' => true,
                        'constraints' => new Length(['min' => 5, 'max' => 5])
                    ]);
            }

        });

            
        $builder
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
