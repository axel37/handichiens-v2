<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomValidator;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
#[CustomValidator\Disponibilite]
class Disponibilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Assert\GreaterThanOrEqual(value: 'now')]
    private ?\DateTimeImmutable $debut = null;

    #[ORM\Column]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Assert\GreaterThan(propertyPath: 'debut')]
    private ?\DateTimeImmutable $fin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'disponibilites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Famille $famille = null;

    /**
     * Chaîne de caractères contenant :
     * - La date de début
     * - La date de fin
     * - Le libellé s'il existe
     *
     * Les années ne sont ajoutées que si il ne s'agit pas de celle en cours.
     * @return string
     */
    public function __toString(): string
    {
        $resultat = '';
        $aujourdhui = new DateTimeImmutable('today');
        $format = 'd F H\hi';

        if ($this->debut->format('Y') !== $aujourdhui->format('Y'))
        {
            $format = 'd F y H\hi';
        }
        $resultat .= $this->debut->format($format);

        if ($this->fin->format('Y') !== $aujourdhui->format('Y'))
        {
            $format = 'd F y H\hi';
        }
        $resultat .= ' - ' . $this->fin->format($format);

        if (isset($this->libelle))
        {
            $resultat .= ' (' . $this->libelle . ')';
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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

}
