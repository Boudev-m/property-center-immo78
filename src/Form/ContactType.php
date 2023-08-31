<?php

/** Form for contact */

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom', 'attr' => [
                    'class' => 'p-1 bg-light rounded-0'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom', 'attr' => [
                    'class' => 'p-1 bg-light rounded-0'
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'p-1 bg-light rounded-0'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail', 'attr' => [
                    'class' => 'p-1 bg-light rounded-0'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message', 'attr' => [
                    'class' => 'p-1 bg-light rounded-0'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
