<?php

namespace App\Form;

use App\Service\LevelService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ChildUser;

class QuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                [
                    'label'=>'Titre',
                    'attr'=> ['class'=>'form-control']
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label'=>"Description",
                    'attr'=> ['class'=>'form-control']
                ]
            )
            ->add('child', EntityType::class,
                [
                    'label'=>'Donner cette quête à',
                    'class'=> ChildUser::class,
                    'choice_label'=> 'name',
                    'multiple'=>false,
                    'required'=>true,
                    'attr'=> [
                        'class'=>'form-control',
                    ],
                    'choices' => $options['parent']->getChildren(),

                    'choice_attr' => function($choice, $key, $value) use ($options) {
                        // adds a class like attending_yes, attending_no, etc
                        return [
                                'data-exp' => $options['ls']->infosFromExp($choice->getExp())['expCurrentLv']
                        ];
                    },
                ]
            )
            ->add('exp', IntegerType::class,
                [
                    'label'=>"Points d'expérience",
                    'attr'=> [
                        'class'=>'form-control',
                        'placeholder' => "Points d'expérience",
                        'min'=>0,
                    ]
                ]
            )
            ->add('goldCoins', IntegerType::class,
                [
                    'label'=>"Pièces d'or",
                    'attr'=> [
                        'class'=>'form-control',
                        'placeholder' => "Pièces d'or",
                        'min'=>0,
                    ]
                ]
            )
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Quest',
            'cascade_validation' => true,
            'parent' => null, // Permet de passer la variable parent depuis le controller
            'ls' => null, // Permet de passer la variable parent depuis le controller
        ]);
    }
}
