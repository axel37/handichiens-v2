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
    public ?string $href = '/';
    public ?string $icones = null;
    public ?string $texte = null;
    public Chien|Famille|Disponibilite|Affectation|Utilisateur|null $entite = null;

    // public ?string $entiteNom = $entite;

}