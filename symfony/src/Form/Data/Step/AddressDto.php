<?php

namespace App\Form\Data\Step;

use Symfony\Component\Validator\Constraints as Assert;

class AddressDto
{
    #[Assert\NotBlank(groups: ['address'])]
    #[Assert\Length(min: 2, max: 50, groups: ['address'])]
    public ?string $adress = null;

    #[Assert\NotBlank(groups: ['address'])]
    #[Assert\Length(min: 2, max: 50, groups: ['address'])]
    public ?string $city = null;

    #[Assert\NotBlank(groups: ['address'])]
    #[Assert\Length(min: 2, max: 50, groups: ['address'])]
    public ?string $zipcode = null;

}