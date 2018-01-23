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
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom *'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail *',
                'required' => false,
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password *'),
                'second_options' => array('label' => 'Repeat Password *'),
            ))
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil',
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'role *',
                'choices' => [
                    'Utilisateur' => "['ROLE_USER',]",
                    'Administrateur' => "['ROLE_USER', 'ROLE_ADMIN']"
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}