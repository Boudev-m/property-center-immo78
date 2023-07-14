<?php

/** Form for search filter */

namespace App\Form;

use App\Entity\Option;
use App\Entity\PropertySearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minSurface', IntegerType::class, [
                'required' => false, // minSurface field not required
                'label' =>  false, // remove label
                'attr' => [
                    'placeholder' => 'Surface minimale'
                ]
            ])
            ->add('maxPrice', IntegerType::class, [
                'required' => false, // maxPrice field not required
                'label' =>  false, // remove label
                'attr' => [
                    'placeholder' => 'Budget maximal'
                ]
            ])
            ->add('options', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('address', null, [
                'required' => false,
                'label' => false
            ])
            ->add('distance', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                    '10 kms' => 10,
                    '100 kms' => 100,
                    '1000 kms' => 1000
                ]
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            // ->add('submit', SubmitType::class, [
            //     'label' => 'Rechercher'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    // Remove prefix in url
    public function getBlockPrefix()
    {
        return '';
    }
}
