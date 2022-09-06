<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AffectationValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\Affectation $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (get_class($value) !== \App\Entity\Affectation::class)
        {
            throw new UnexpectedTypeException($value, \App\Entity\Affectation::class);
        }

        $chien = $value->getChien();
        if (!isset($chien))
        {
            return;
        }

        $affectations = $value->getChien()->getAffectations();

        foreach ($affectations as $affectation) {
            if (
                ($affectation->getFin() > $value->getDebut()) && ($affectation->getDebut() < $value->getFin())
            )
            {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $affectation)
                    ->addViolation();
            }
        }
    }
}
