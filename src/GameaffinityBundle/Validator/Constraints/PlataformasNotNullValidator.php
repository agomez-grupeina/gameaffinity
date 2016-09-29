<?php

namespace GameaffinityBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PlataformasNotNullValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {
        if (sizeof($value) < 1) {
            $this->context->buildViolation($constraint->message)
                    ->addViolation();
        }
    }

}
