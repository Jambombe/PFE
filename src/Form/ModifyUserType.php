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

class ModifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Prénom',
                        'class' => 'ui input form-control',
                    ],
                ]
            )
            ->add('currentPassword',PasswordType::class,
                [
                    'mapped' => false,
                    'label' => false,
                    'attr' => ['placeholder' => 'Mot de passe actuel','class'=>'form-control'],

                ]
            )
            ->add('plainPassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options'  => array(
                        'label' => false,
                        'attr' => ['placeholder' => 'Nouveau mot de passe','class'=>'form-control'],
                    ),
                    'second_options' => array(
                        'label' => false,
                        'attr' => ['placeholder' => 'Confirmer le nouveau mot de passe','class'=>'form-control'],
                        ),
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array('max' => 4096))
                    ),
                    'attr' => [
                        'class' => 'ui input form-control',
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
