<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\Category;
use AppBundle\Entity\District;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainPicture', FileType::class, [
                'required' => false
            ])
            ->add('subPictures', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse postale du projet *',
                'required' => true
            ])
            ->add('name', TextType::class, [
                'label' => 'Titre du projet *',
                'required' => true
            ])
            ->add('district', EntityType::class, [
                'class' => District::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => 'Arrondissement',
                'required' => true
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de début du contrat *',
                'required' => true
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label' => 'Catégorie *',
                'required' => true
            ])
            ->add('objectif', TextareaType::class, [
                'label' => 'Objectif du projet *',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du projet *',
                'required' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Project::class
        ));
    }

}
