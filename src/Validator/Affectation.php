<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Vérifie que l'affectation n'entre pas en conflit avec une autre
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Affectation extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Ce chien est déjà affecté sur cette période : {{ value }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
