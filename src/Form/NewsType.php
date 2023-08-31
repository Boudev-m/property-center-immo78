<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['attr' => [
                'title' => 'Titre de l\'article',
                'class' => 'border border-success bg-light rounded-0'
            ]])
            ->add('text', null, ['attr' => [
                'title' => 'Texte de l\'article',
                'class' => 'border border-success bg-light rounded-0',
                'rows' => 10
            ]])
            ->add('image_file', FileType::class, [
                'label' => 'Charger une image',
                'required' => false,
                'attr' => [
                    'class' => 'border border-success bg-light rounded-0'
                ]
            ])
            // ->add('image_name')
            // ->add('created_at')
            // ->add('updated_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'translation_domain' => 'forms'
        ]);
    }
}
