<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('flash')]
class FlashComponent
{
    public string $type = "notice";
    public string $message;
}