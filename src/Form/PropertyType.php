<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Titre', 'attr' => ['title' => 'Titre du bien', 'class' => 'border border-primary']])  // this is a way to translate
            ->add('description')
            ->add('surface', null, ['attr' => [
                'min' => 0,
                'placeholder' => 60
            ]])
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price', null, ['attr' => [
                'min' => 0,
                'placeholder' => 100000
            ]])
            ->add('heat', ChoiceType::class, ['choices' => $this->getChoices()]) // display heat name instead of int
            ->add('options', EntityType::class, [
                'required' => false,
                'class' => Option::class,       // Option from Entity, not Form
                'choice_label' => 'name',       // option name as input label
                'expanded' => true,             // change selecte input to checkbox
                'multiple' => true,              // multitple choice available
                'attr' => [
                    'class' => 'd-flex justify-content-evenly'
                ]
            ])
            ->add('pictureFiles', FileType::class, [
                'label' => 'Importer des images',
                'required' => false,
                'multiple' => true
            ])
            ->add('city')
            ->add('address')
            ->add('postal_code')
            ->add('latitude', HiddenType::class)    // hide the input, because not need in the form
            ->add('longitude', HiddenType::class)
            ->add('sold', null, [
                'label' => 'Vendu (cochez la case si le bien a été vendu)',
            ])
            // ->add('save', SubmitType::class, [   // Not need because the form includes submit button
            //     'label' => 'Enregistrer'
            // ])
            // ->add('created_at')  Not need for the form
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
