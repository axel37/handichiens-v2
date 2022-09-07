<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomValidator;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
#[CustomValidator\AffectationDisponibilite]
#[CustomValidator\Affectation]
class Affectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Assert\GreaterThanOrEqual(value: 'now')]
    private ?\DateTimeImmutable $debut = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Assert\GreaterThan(propertyPath: 'debut')]
    private ?\DateTimeImmutable $fin = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Chien $chien = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Famille $famille = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $confirme = false;

    /**
     * Chaîne de caractères contenant :
     * - La date de début
     * - La date de fin
     * - Si l'affectation n'est pas confirmée
     *
     * Les années ne sont ajoutées que si il ne s'agit pas de celle en cours.
     * @return string
     */
    public function __toString(): string
    {
        $resultat = $this->chien . ' / Famille ' . $this->famille . ' : ';
        $aujourdhui = new DateTimeImmutable('today');
        $format = 'd F H\hi';

        if ($this->debut->format('Y') !== $aujourdhui->format('Y')) {
            $format = 'd F y H\hi';
        }
        $resultat .= $this->debut->format($format);

        if ($this->fin->format('Y') !== $aujourdhui->format('Y')) {
            $format = 'd F y H\hi';
        }
        $resultat .= ' - ' . $this->fin->format($format);

        if (!$this->confirme) {
            $resultat .= ' (Non confirmé)';
        }

        return $resultat;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?\DateTimeImmutable
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeImmutable $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeImmutable
    {
        return $this->fin;
    }

    public function setFin(\DateTimeImmutable $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getChien(): ?Chien
    {
        return $this->chien;
    }

    public function setChien(?Chien $chien): self
    {
        $this->chien = $chien;

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    public function isConfirme(): ?bool
    {
        return $this->confirme;
    }

    public function setConfirme(bool $confirme): self
    {
        $this->confirme = $confirme;

        return $this;
    }

}
