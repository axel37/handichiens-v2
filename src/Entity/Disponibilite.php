<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
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
     * Vérifie que la disponibilité n'entre pas en conflit avec d'autres de la même famille
     *
     * - Vrai : Pas de conflit
     * - Faux : Conflit !
     * @return bool
     */
    #[Assert\IsTrue(
        message: "Ces dates rentrent en conflit avec une disponibilité existante."
    )]
    public function isNotConflicting(): bool
    {
        $disponibilites = $this->famille->getDisponibilites();

        $isNotConflicting = $disponibilites->forAll(
            function ($key, $value)
            {
                /*
                La nouvelle disponibilité doit :
                  - débuter après la fin d'une autre
                    OU
                  - prendre fin avant le début d'une autre
                */
                return ($value->getFin() < $this->debut) || ($value->getDebut() > $this->fin);
            }
        );

        return $isNotConflicting;
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
