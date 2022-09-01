<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_class', [$this, 'getClass']),
            new TwigFunction('get_fqcn', [$this, 'getFQCN']),
            new TwigFunction('instance_of', [$this, 'instanceOf']),
        ];
    }


    /**
     * Renvoie la classe d'un objet (sans son namespace)
     * @param Object $object
     * @return string La classe de l'objet
     */
    public function getClass(Object $object): string
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    /**
     * Renvoie le nom pleinement qualifié d'un objet (avec namespace)
     * @param Object $object
     * @return string Le FQCN de la classe
     */
    public function getFQCN(Object $object): string
    {
        return (new \ReflectionClass($object))->getName();
    }

    /**
     * Teste si un objet est l'instance d'une classe.
     *
     * **!! Échapper (doubler) les anti-slashs : (App\\\Entity\\\Xyz plutôt que App\Entity\Xyz) !!**
     * @param Object $object
     * @param string $class
     * @return bool
     */
    public function instanceOf(Object $object, string $class): bool
    {
        return $object instanceof $class;
    }
}
