<?php

namespace App\Form\Data;

use App\Form\Data\Step\AddressDto;
use App\Form\Data\Step\ConfirmationDto;
use App\Form\Data\Step\UsersDto;
use App\Form\Data\Step\PostsDto;
use App\Form\Data\Step\AdressDto;
use Symfony\Component\Validator\Constraints as Assert;

class UsersRegistrationDto
{
    public string $currentStep = 'user';

    #[Assert\Valid(groups: ['users'])]
    public ?UsersDto $users = null;

    #[Assert\Valid(groups: ['address'])]
    public ?AddressDto $adress = null;

    #[Assert\Valid(groups: ['posts'])]
    public ?PostsDto $posts = null;

    #[Assert\Valid(groups: ['confirmation'])]
    public ?ConfirmationDto $confirmation = null;
}