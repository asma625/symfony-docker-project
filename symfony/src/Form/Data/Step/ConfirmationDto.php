<?php

namespace App\Form\Data\Step;

use Symfony\Component\Validator\Constraints as Assert;

class ConfirmationDto
{
    #[Assert\IsTrue(
        message: 'Vous devez accepter les CGU',
        groups: ['confirmation']
    )]
    public bool $acceptTerms = false;
}