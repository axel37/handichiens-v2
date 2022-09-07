<?php

namespace App\Components;

use App\Entity\Chien;
use App\Entity\Famille;
use App\Entity\Affectation;
use App\Entity\Utilisateur;
use App\Entity\Disponibilite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Composant carte
 *
 * Accepte une entité **OU** du texte, une/des icônes et un lien.
 *
 * @see mount
 */
#[AsTwigComponent('carte')]
class CarteComponent
{

    /**
     * Tableau représentant le texte à écrire dans le composant.
     *
     * Un élément = une ligne.
     *
     * Si un string est passé dans mount(), il sera transformé en array à 1 élément.
     * @var array
     */
    #[ExposeInTemplate]
    private array $texte;

    /**
     * Cible du lien.
     * @var string
     */
    #[ExposeInTemplate]
    private string $href;

    /**
     * Chemin des icônes
     * @var array
     */
    #[ExposeInTemplate]
    private array $icone;

    /**
     * Cible du style.
     * @var string
     */
    #[ExposeInTemplate]
    private ?string $class;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        // Utilisé pour générer les routes
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Paramètres acceptés à l'instanciation du composant.
     *
     * Si $entite est renseignée, les autres paramètres seront ignorés.
     *
     * Si $entite *n'est pas* renseignée, les autres paramètres **doivent** être renseignés.
     * @param Chien|Famille|Disponibilite|Affectation|Utilisateur|null $entite
     * @param array|string|null $texte Une ligne (string) ou un élément par ligne (array)
     * @param string|null $href La destination du lien
     * @param array|string|null $icone La/les icônes à afficher
     * @param string|null $class La destination du style
     * @return void
     */
    public function mount(
        null|Chien|Famille|Disponibilite|Affectation|Utilisateur $entite = null,
        null|array|string                                        $texte = null,
        ?string                                                  $href = null,
        ?string                                                  $icone = null,
        ?string                                                  $class = null,
    ): void
    {
        // Si une entité a été passée, remplacer le texte, le href et l'icône
        if (isset($entite)) {
            $type = (new \ReflectionClass($entite))->getShortName();

            // TODO : hrefs
            // TODO : Icônes

            // Définir le contenu de la carte en fonction du type de l'entité
            switch ($type):
                case 'Famille':
                    $texte = [
                        $entite->getNom() . ' ' . $entite->getPrenom(),
                        $entite->getAdresse()
                    ];
                    $href = $this->urlGenerator->generate('app_accueil');
                    $icone = '/';
                    break;
                case 'Utilisateur':
                    $texte = [
                        $entite->getNom() . ' ' . $entite->getPrenom()
                    ];
                    $href = $this->urlGenerator->generate('app_accueil');
                    $icone = '/';
                    break;
                case 'Disponibilite':
                    $texte = [
                        $entite->getDebut()->format('d.m.Y H:i:s'),
                        $entite->getFin()->format('d.m.Y H:i:s')
                    ];
                    $href = $this->urlGenerator->generate('app_accueil');
                    $icone = '/';
                    break;
                default:
                    $texte = ['Entité invalide'];
                    $href = '/';
                    $icone = '/';
                    $class = 'carte__lien';
            endswitch;
        }

        if (is_string($texte)) {
            $texte = [$texte];
        }
        if (is_string($icone)) {
            $icone = [$icone];
        }

        // Si, à ce point du code, une de ces variables est null, le composant n'a pas été créé correctement.
        try {
            $this->texte = $texte;
            $this->href = $href;
            $this->icone = $icone;
            $this->class = $class;
        } catch (\TypeError $exception) {
            throw new \TypeError('Erreur lors de la création du composant CarteComponent. 
            Avez-vous passé tous les paramètres requis à son instantiation ? 
            (' . $exception->getMessage() . ')');
        }
    }

    /**
     * @return array
     */
    public function getTexte(): array
    {
        return $this->texte;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return array
     */
    public function getIcone(): array
    {
        return $this->icone;
    }

    /**
     * @return string
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

}