<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', ButtonType::class)
            ->add('address', TextType::class, [
                'label' => 'Votre adresse',
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => 'Catégorie',
                'required' => false,
                'multiple' => true
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'statut du projet',
                'choices' => [
                    'En préparation' => 1,
                    'En cours' => 2,
                    'Fini' => 3,
                ],
                'required' =>false,
                'multiple' => true
            ])
            ->add('range', ChoiceType::class, [
                'label' => 'Km',
                'choices' => [
                    '2'     => 2,
                    '5'     => 5,
                    '10'    => 10,
                    '20'    => 20
                ],
                'required' =>false
            ])
            ->add('search', ButtonType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

}
