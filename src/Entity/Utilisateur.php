<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['utilisateur' => Utilisateur::class, 'famille' => Famille::class])]
#[Uploadable]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email()]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $prenom = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $telephone = null;

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
    #[UploadableField(mapping: 'utilisateur', fileNameProperty: 'photoFileName')]
    private UploadedFile|File|null $photo = null;

    /**
     * Requis par Vich/UploaderBundle
     * @see setPhoto
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $dateModificationPhoto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    private function getDateModification(): ?DateTimeImmutable
    {
        return $this->dateModificationPhoto;
    }

    private function setDateModification(?DateTimeImmutable $dateModification): self
    {
        $this->dateModificationPhoto = $dateModification;

        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    public function unserialize(string $data)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($data);
    }
}
