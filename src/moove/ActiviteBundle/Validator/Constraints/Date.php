<?php
// src/moove/ActiviteBundle/Validator/Date.php
 
namespace moove\ActiviteBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
 
/**
 * @Annotation
 */
class Date extends Constraint
{
    public $message = "Vous devez rentrer une date supérieure à celle d'aujourd'hui.";
}