<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('bouton')]
class BoutonComponent
{
    public string $type = "principal";
    public string $href;
    public string $texte;
}
