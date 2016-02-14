<?php
// src/moove/ActiviteBundle/Validator/DateValidator.php
 
namespace moove\ActiviteBundle\Validator\Constraints;
 
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
 
class DateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();
        $date = $now->diff($value);
        if(!($date->y >= 0 && $date->m >= 0 && $date->d >= 1 && $date->h >= 0 && $date->i >= 0 && $date->s >= 0))
        {
            $this->context->buildViolation($constraint->message)->addViolation();
             
        }      
    }
}