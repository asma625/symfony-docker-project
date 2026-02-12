<?php

namespace App\Form\Type\Step;

use App\Form\Data\Step\PostsDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsStepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'adresse',
                'attr' => ['placeholder' => 'Votre adresse'],
            ])
            ->add('content', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'ville'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostsDto::class,
        ]);
    }
}