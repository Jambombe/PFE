<?php

namespace App\Form;

use App\Entity\ParentUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// Type
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
// Constraints
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Email',
                        'class' => 'ui input',
                    ],
                ]
            )
            ->add('plainPassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options'  => array(
                        'label' => false,
                        'attr' => ['placeholder' => 'Mot de passe'],
                    ),
                    'second_options' => array(
                        'label' => false,
                        'attr' => ['placeholder' => 'Confirmer le mot de passe'],
                        ),
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array('max' => 4096))
                    ),
                    'attr' => [
                        'class' => 'ui input',
                    ],
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Prénom',
                        'class' => 'ui input',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ParentUser::class,
        ));
    }
}
