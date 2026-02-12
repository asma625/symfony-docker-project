<?php

namespace App\Form\Type\Step;

use App\Form\Data\Step\AddressDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressStepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adress', TextType::class, [
                'label' => 'adresse',
                'attr' => ['placeholder' => 'Votre adresse'],
            ])
            ->add('city', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'ville'],
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'adresse postale'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressDto::class,
        ]);
    }
}