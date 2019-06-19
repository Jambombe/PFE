<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'cascade_validation' => true,
        ]);
    }
}
