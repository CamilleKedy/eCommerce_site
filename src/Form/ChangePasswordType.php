<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{

    // Formulaire du changement de mot de passe
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'disabled' => true      //Empêche à l'utilisateur de changer son email
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'disabled' => true      //Empêche à l'utilisateur de changer son prenom
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'disabled' => true      //Empêche à l'utilisateur de changer son nom
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Votre mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuiller saisir votre mot de passe actuel'      //Le fait de définir un passwordtype vide directement le champ du mdp
                ]
            ])
            ->add('new_password', RepeatedType::class, [    
                'type' => PasswordType::class,
                'mapped' => false, //pour dire à symfony que 'new_password' ne fait pas partie des propriétés de l'entité user et qu'il n'essaie pas de le lier à celle-ci
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Saisissez le nouveau mot de passe',
                'required' => true, 
                'first_options' => [ 
                    'label' => 'Votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre nouveau mot de passe'     
                    ] 
                ],
                'second_options' => [ 
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot de passe'   
                    ]
                ]
                
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour",            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
