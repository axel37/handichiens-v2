<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('bouton')]
class BoutonComponent
{
    public string $type;
    public string $texte;
}
