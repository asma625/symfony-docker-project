<?php

namespace App\Form\Type;

use App\Form\Data\TravelRegistrationDto;
use App\Form\Data\UsersRegistrationDto;
use App\Form\Type\Step\AdressStepFormType;
use App\Form\Type\Step\ConfirmationFormType;
use App\Form\Type\Step\PostsStepFormType;
use App\Form\Type\Step\UserStepFormType;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersRegistrationFormType extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        // Étape 1 : Informations personnelles
        $builder->addStep('Users', UserStepFormType::class);

        // Étape 2 : Préférences
        $builder->addStep('Adress', AdressStepFormType::class);

        // Étape 3 : Préférences
        $builder->addStep('posts', PostsStepFormType::class);

        // Étape 4 : Confirmation
        $builder->addStep('confirmation', ConfirmationFormType::class);

        // Ajout du navigateur
        $builder->add('navigator', UsersNavigatoreFormType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UsersRegistrationDto::class,
            'step_property_path' => 'currentStep',
        ]);
    }
}