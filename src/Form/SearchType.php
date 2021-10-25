<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    
    // Création du formulaire de recherche
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Pour la recherche textuelle
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche ...',
                    'class' => 'form-control-sm'
                ]
            ])
            // Permet de lier une propriété du formulaire et dire à SF qu'on veut que ça représente une entité
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class, //A quoi sera lié les données de ces categories ? A la classe Category
                'multiple' =>true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    // Liaison à la search class de symfony
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}