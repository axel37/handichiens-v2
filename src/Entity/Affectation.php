<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
class Affectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Assert\GreaterThanOrEqual(value: 'today')]
    private ?\DateTimeImmutable $debut = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Assert\GreaterThan(propertyPath: 'debut')]
    private ?\DateTimeImmutable $fin = null;

    #[ORM\Column]
    private ?bool $estConfirme = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Chien $chien = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Famille $famille = null;

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

    public function isEstConfirme(): ?bool
    {
        return $this->estConfirme;
    }

    public function setEstConfirme(bool $estConfirme): self
    {
        $this->estConfirme = $estConfirme;

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

}
