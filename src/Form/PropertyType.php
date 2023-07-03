<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Titre'])  // this is a way to translate
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class, ['choices' => $this->getChoices()]) // display heat name instead of int
            ->add('options', EntityType::class, [
                'class' => Option::class,       // Option from Entity, not Form
                'choice_label' => 'name',   // option name as input label
                'multiple' => true  // multitple choice available
            ])
            ->add('city')
            ->add('address')
            ->add('postal_code')
            ->add('sold')
            //->add('created_at')  Not need for the form
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]);
    }

    // Get heat name instead of int
    private function getChoices()
    {
        $choices = Property::HEAT;
        $result = [];
        foreach ($choices as $key => $value) {
            $result[$value] = $key;
        }
        return $result;
    }
}
