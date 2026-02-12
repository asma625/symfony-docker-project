<?php

namespace App\Form\Data\Step;

use Symfony\Component\Validator\Constraints as Assert;

class PostsDto
{
    #[Assert\NotBlank(groups: ['posts'])]
    #[Assert\Length(min: 2, max: 50, groups: ['posts'])]
    public ?string $title = null;

    #[Assert\NotBlank(groups: ['posts'])]
    #[Assert\Length(min: 2, max: 50, groups: ['posts'])]
    public ?string $content = null;

}