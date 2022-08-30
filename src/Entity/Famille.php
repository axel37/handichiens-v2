<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FamilleRepository::class)]
class Famille extends Utilisateur
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $adresse = null;

    #[ORM\Column(length: 125)]
    #[Assert\NotBlank]
    private ?string $ville = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex('/\d{5}/',
    message: "{{value}} n'est pas un code postal valide.")]
    private ?string $codePostal = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\OneToMany(mappedBy: 'famille', targetEntity: Affectation::class)]
    private Collection $affectations;

    #[ORM\OneToMany(mappedBy: 'famille', targetEntity: Disponibilite::class)]
    private Collection $disponibilites;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->disponibilites = new ArrayCollection();
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();

        // Une instance de Famille a automatiquement le rôle "ROLE_FAMILLE"
        $roles[] = 'ROLE_FAMILLE';

        return array_unique($roles);
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setFamille($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getFamille() === $this) {
                $affectation->setFamille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Disponibilite>
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): self
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->add($disponibilite);
            $disponibilite->setFamille($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->removeElement($disponibilite)) {
            // set the owning side to null (unless already changed)
            if ($disponibilite->getFamille() === $this) {
                $disponibilite->setFamille(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->getNom();
    }
}
