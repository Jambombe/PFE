<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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
                    'attr'=> ['class'=>'form-control'],
                    'choices' => $options['parent']->getChildren(),
                ]
            )
//            ->add('exp', TextType::class,
//                [
//                    'mapped' => false,
//                    'attr' => [
//                        'value'=>"0,50",
//                        'class' => 'slider form-control',
//                        'data-slider-min' => "0",
//                        'data-slider-max' => "100",
//                        'data-slider-step' => "1",
//                        'data-slider-value' => "[0,50]",
//                        'data-slider-orientation' => 'horizontal',
//                        'data-slider-selection' => 'before',
//                        'data-slider-tooltip' => 'show',
//                        'data-slider-id' => 'yellow',
//                        'data-value' => "[0,50]",
//                        'style' => 'display: none;',
//                    ],
//                ]
//            )
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Quest',
            'cascade_validation' => true,
            'parent' => null, // Permet de passer la variable parent depuis le controller
        ]);
    }
}