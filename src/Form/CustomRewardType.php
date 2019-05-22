<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ChildUser;

class CustomRewardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label'=>'Nom',
                    'attr'=> ['class'=>'form-control']
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label'=>"Description",
                    'attr'=> ['class'=>'form-control']
                ]
            )
            ->add('goldCoinPrice', IntegerType::class,
                [
                    'label'=>"Pièces d'or",
                    'attr'=> [
                        'class'=>'form-control',
                        'placeholder' => "Prix en pièces d'or",
                        'min'=>0,
                    ]
                ]
            )
            ->add('image', TextType::class,
                [
                    'required'=>false,
                    'label'=>'Image',
                    'attr'=> [
                        'placeholder' => 'http:// ... .jpg',
                        'class'=>'form-control'
                    ]
                ]
            )
        ;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\CustomReward',
            'cascade_validation' => true,
//            'parent' => null, // Permet de passer la variable parent depuis le controller
        ]);
    }
}
