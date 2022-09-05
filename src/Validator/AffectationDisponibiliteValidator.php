<?php

namespace App\Validator;

use App\Entity\Affectation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AffectationDisponibiliteValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\AffectationDisponibilite $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (get_class($value) !== Affectation::class)
        {
            throw new UnexpectedTypeException($value, Affectation::class);
        }

        $disponibilites = $value->getFamille()->getDisponibilites();

        $estDansDisponibilite = $disponibilites->exists(
            function ($key, $disponibilite) use ($value) {
                return $disponibilite->getDebut() <= $value->getDebut() && $disponibilite->getFin() >= $value->getFin();
            }
        );

        if (!$estDansDisponibilite)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
