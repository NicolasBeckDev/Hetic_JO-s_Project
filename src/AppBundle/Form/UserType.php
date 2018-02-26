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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (in_array($options['type'], ['adminNew','userEdit','userNew','adminNew'])){
            $builder
                ->add('firstname', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'app.page.client.user.inputs.firstname'
                    ]
                ]);
        }
        if (in_array($options['type'], ['adminNew','userEdit','userNew','adminNew'])){
            $builder
                ->add('lastname', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'app.page.client.user.inputs.lastname'
                    ]
                ]);
        }
        if (in_array($options['type'], ['adminNew','userEdit','userNew','forgotten','adminNew'])){
            $builder
                ->add('email', EmailType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'app.page.client.user.inputs.email'
                    ]
                ]);
        }
        if (in_array($options['type'], ['adminNew','userEdit','userNew','adminNew'])){
            $builder
                ->add('picture', FileType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'app.page.client.user.inputs.picture'
                    ]
                ]);
        }
        if (in_array($options['type'], ['adminNew','adminNew'])){
            $builder
                ->add('roles', ChoiceType::class, [
                    'label' => 'role *',
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN'
                    ],
                    'required' => true,
                ]);
        }
        if (in_array($options['type'], ['adminNew','userNew','reinitialisation','userEdit'])){
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent correspondre.',
                    'options' => [
                        'attr' => [
                            'class' => 'password-field'
                        ]
                    ],
                    'required' => false,
                    'first_options'  => [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'app.page.client.user.inputs.password'
                        ]
                    ],
                    'second_options' => [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'app.page.client.user.inputs.confirm_password'
                        ]
                    ],
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