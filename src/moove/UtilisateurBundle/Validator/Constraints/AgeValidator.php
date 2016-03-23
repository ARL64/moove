<?php
// src/moove/UtilisateurBundle/Validator/AgeValidator.php
 
namespace moove\UtilisateurBundle\Validator\Constraints;
 
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
 
class AgeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();
        if($value === null)
            $value = new \Datetime();
        $age = $now->diff($value);
        if($age->y < 13)
        {
            $this->context->buildViolation($constraint->message)->addViolation();
             
        }      
    }
}