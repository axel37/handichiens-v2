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
    public string $type = "defaut" | "multiline" | "affectation" | "famille" | "disponibilite-famille" | "disponibilite" | "chien" | "utilisateur";
    public ?string $href = '/';
    
    public ?string $icone1 = null;
    public ?string $icone2 = null;

    public ?string $texte1 = null;
    public ?string $texte2 = null;
    
    public Chien|Famille|Disponibilite|Affectation|Utilisateur|null $entite = null;
}