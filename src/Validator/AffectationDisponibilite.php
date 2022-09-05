<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Vérifie que l'affectation correspond à une disponibilité de la famille
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class AffectationDisponibilite extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Les dates choisies ne correspondent à aucune disponibilité de la famille';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
