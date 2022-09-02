<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('flash')]
class FlashComponent
{
    public string $type = 'success';
    public string $message;
}

// Comment créer une alerte Flash ?
// Dans le controller :
// $this->addFlash(
//     'success' OU 'warning' OU 'error',
//     'Message'
// );