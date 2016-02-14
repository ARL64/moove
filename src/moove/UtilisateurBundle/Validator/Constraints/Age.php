<?php
// src/moove/UtilisateurBundle/Validator/Age.php
 
namespace moove\UtilisateurBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
 
/**
 * @Annotation
 */
class Age extends Constraint
{
    public $message = 'Vous devez avoir au moins 13 ans.';
}