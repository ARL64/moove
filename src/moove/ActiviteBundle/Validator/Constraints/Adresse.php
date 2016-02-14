<?php
// src/moove/ActiviteBundle/Validator/Adresse.php
 
namespace moove\ActiviteBundle\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
 
/**
 * @Annotation
 */
class Adresse extends Constraint
{
    public $message = 'Vous devez entrer une adresse valide.';
}