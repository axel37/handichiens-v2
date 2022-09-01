<?php

namespace App\Components;

use App\Entity\Chien;
use App\Entity\Famille;
use App\Entity\Affectation;
use App\Entity\Utilisateur;
use App\Entity\Disponibilite;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('carte')]
class CarteComponent
{
    public string $href;
    public string $icone1;
    public string $icone2;
    public string|array $texte;
    public Chien|Famille|Disponibilite|Affectation|Utilisateur $entite;
}