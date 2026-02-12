<?php

namespace App\Form\Data\Step;

use Symfony\Component\Validator\Constraints as Assert;

class UsersDto
{
    #[Assert\NotBlank(groups: ['users'])]
    #[Assert\Length(min: 2, max: 50, groups: ['users'])]
    public ?string $firstName = null;

    #[Assert\NotBlank(groups: ['users'])]
    #[Assert\Length(min: 2, max: 50, groups: ['users'])]
    public ?string $lastName = null;

}