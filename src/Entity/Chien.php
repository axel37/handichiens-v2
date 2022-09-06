<?php

namespace App\Entity;

use App\Repository\ChienRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: ChienRepository::class)]
#[Uploadable]
class Chien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(
        value: 'today'
    )]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $race = null;

    #[ORM\OneToMany(mappedBy: 'chien', targetEntity: Affectation::class)]
    private Collection $affectations;

    /**
     * Le nom du fichier représentant la photo de profil
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoFileName = null;

    /**
     * Le fichier représentant la photo. N'est pas stocké en base.
     * @see $photoFileName
     * @var UploadedFile|File|null
     */
    #[Assert\File(
        maxSize: "1M",
        mimeTypes: [
            "image/*"
        ],
        mimeTypesMessage: "Le fichier doit être une image."
    )]
    #[UploadableField(mapping: 'chien', fileNameProperty: 'photoFileName')]
    private UploadedFile|File|null $photo = null;

    /**
     * Requis par Vich/UploaderBundle
     * @see setPhoto
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $dateModificationPhoto = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $supprime = false;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getNom();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeImmutable $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): self
    {
        $this->race = $race;

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
            $affectation->setChien($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getChien() === $this) {
                $affectation->setChien(null);
            }
        }

        return $this;
    }

    public function getPhotoFileName(): ?string
    {
        return $this->photoFileName;
    }

    public function setPhotoFileName(?string $photoFileName): self
    {
        $this->photoFileName = $photoFileName;

        return $this;
    }

    /**
     * @return UploadedFile|File|null
     */
    #[Ignore] // Le Serializer ignorera ce champ
    public function getPhoto(): UploadedFile|File|null
    {
        return $this->photo;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param UploadedFile|File|null $photo
     */
    #[Ignore] // Le Serializer ignorera ce champ
    public function setPhoto(UploadedFile|File|null $photo): self
    {
        $this->photo = $photo;

        if (null !== $photo) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateModificationPhoto = new DateTimeImmutable();
        }
        return $this;
    }


    /**
     * Supprime les données du chien
     *
     * @return $this
     */
    public function anonymiser(): self
    {
        // TODO : Cela aurait plus de sens si les valeurs devenaient NULL plutôt que des valeurs arbitraires !
        // TODO : Photo pas supprimée
        $this
            ->setNom('Chien supprimé')
            ->setPhoto(null)
            ->setRace('Chien supprimé')
            ->setDateNaissance(new DateTimeImmutable('0000-01-01'))
            ->setSupprime(true);

        return $this;
    }

    private function getDateModification(): ?DateTimeImmutable
    {
        return $this->dateModificationPhoto;
    }

    private function setDateModification(?DateTimeImmutable $dateModification): self
    {
        $this->dateModificationPhoto = $dateModification;

        return $this;
    }

    public function isSupprime(): ?bool
    {
        return $this->supprime;
    }

    public function setSupprime(bool $supprime): self
    {
        $this->supprime = $supprime;

        return $this;
    }

}
