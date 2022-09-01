<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('flash')]
class FlashComponent
{
    public string $type = 'succes';
    public string $message;
}

// Comment créer une alerte Flash ?
// Dans le controller :
// $this->addFlash(
//     'succes' OU 'avertissement' OU 'erreur',
//     'Message'
// );