<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DisponibiliteValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\Disponibilite $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (get_class($value) !== \App\Entity\Disponibilite::class)
        {
            throw new UnexpectedTypeException($value, \App\Entity\Disponibilite::class);
        }


        $disponibilites = $value->getFamille()->getDisponibilites();

        foreach ($disponibilites as $disponibilite) {
            if (
                ($disponibilite->getFin() > $value->getDebut()) && ($disponibilite->getDebut() < $value->getFin())
            )
            {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $disponibilite)
                    ->addViolation();
            }
        }


    }
}
