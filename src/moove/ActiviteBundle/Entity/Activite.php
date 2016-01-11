<?php
// src/moove/ActiviteBundle/Entity/Activite.php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="activite")
 */
class Activite
{
    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateHeureRDV", type="datetime")
     */
    private $dateHeureRDV;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="duree", type="time")
     */
    private $duree;

    /**
     * @var integer
     * 
     * @ORM\Column(name="nbPlaces", type="integer")

     */
    private $nbPlaces;

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateFermeture", type="datetime")
     */
    private $dateFermeture;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="estTerminee", type="boolean")
     */
    private $estTerminee;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Utilisateur")
     */
    private $organisateur;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateHeureRDV
     *
     * @param \DateTime $dateHeureRDV
     * @return Activite
     */
    public function setDateHeureRDV($dateHeureRDV)
    {
        $this->dateHeureRDV = $dateHeureRDV;

        return $this;
    }

    /**
     * Get dateHeureRDV
     *
     * @return \DateTime 
     */
    public function getDateHeureRDV()
    {
        return $this->dateHeureRDV;
    }

    /**
     * Set duree
     *
     * @param \DateTime $duree
     * @return Activite
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return \DateTime 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set nbPlaces
     *
     * @param integer $nbPlaces
     * @return Activite
     */
    public function setNbPlaces($nbPlaces)
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    /**
     * Get nbPlaces
     *
     * @return integer 
     */
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Activite
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Activite
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateFermeture
     *
     * @param \DateTime $dateFermeture
     * @return Activite
     */
    public function setDateFermeture($dateFermeture)
    {
        $this->dateFermeture = $dateFermeture;

        return $this;
    }

    /**
     * Get dateFermeture
     *
     * @return \DateTime 
     */
    public function getDateFermeture()
    {
        return $this->dateFermeture;
    }

    /**
     * Set estTerminee
     *
     * @param boolean $estTerminee
     * @return Activite
     */
    public function setEstTerminee($estTerminee)
    {
        $this->estTerminee = $estTerminee;

        return $this;
    }

    /**
     * Get estTerminee
     *
     * @return boolean 
     */
    public function getEstTerminee()
    {
        return $this->estTerminee;
    }
}
