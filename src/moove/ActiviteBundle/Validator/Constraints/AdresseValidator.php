<?php
// src/moove/ActiviteBundle/Validator/AdresseValidator.php
 
namespace moove\ActiviteBundle\Validator\Constraints;
 
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

require_once __DIR__ . '/../../../../../vendor/jstayton/google-maps-geocoder/src/GoogleMapsGeocoder.php';

class AdresseValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        
        // On créé un objet GoogleMapsGeocoder prenant en paramètre l'adresse du lieu $adresse
        $Geocoder = new \GoogleMapsGeocoder();
        $Geocoder->setAddress($value);
        // On test la valeur
        $reponse = $Geocoder->geocode();
        if($reponse['status'] != "OK" && !is_null($value))
        {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}