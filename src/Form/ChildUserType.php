<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChildUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label'=>'Prénom',
                    'attr'=> ['class'=>'form-control']
                ]
            )
            ->add('pseudo', TextType::class,
                [
                    'label'=>"Nom d'aventurier",
                    'attr'=> ['class'=>'form-control']
                ]
            )
            ->add('password', TextType::class,
                [
                    'label'=>'Mot de passe',
                    'attr'=> ['class'=>'form-control']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => 'App\Entity\ChildUser'
        ]);
    }
}
