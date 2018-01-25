<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array($options['type'], ['new'])){
            $builder
                ->add('firstname', TextType::class, [
                    'label' => 'Prénom *'
                ]);
        }
        if (in_array($options['type'], ['new'])){
            $builder
                ->add('lastname', TextType::class, [
                    'label' => 'Nom *'
                ]);
        }
        if (in_array($options['type'], ['new'])){
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'E-mail *',
                    'required' => false,
                ]);
        }
        if (in_array($options['type'], ['new'])){
            $builder
                ->add('picture', FileType::class, [
                    'label' => 'Photo de profil',
                    'required' => false
                ]);
        }
        if (in_array($options['type'], ['new'])){
            $builder
                ->add('roles', ChoiceType::class, [
                    'label' => 'role *',
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_USER;ROLE_ADMIN'
                    ]
                ]);
        }
        if (in_array($options['type'], ['new'])){
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent correspondre.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options'  => array('label' => 'Mot de passe *'),
                    'second_options' => array('label' => 'Répeter le mot de passe *'),
                ])
                ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'type' => ''
        ));
    }
}