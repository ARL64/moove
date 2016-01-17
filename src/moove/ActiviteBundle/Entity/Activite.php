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
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Niveau")
     */
    private $niveauRequis;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Sport")
     */
    private $sportPratique;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu")
     */    
    private $lieuRDV;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu")
     */
    private $lieuDepart;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu")
     */
    private $lieuArrivee;


    /* /!\ Début des get & set /!\ */
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

    /**
     * Set organisateur
     *
     * @param \moove\ActiviteBundle\Entity\Utilisateur $organisateur
     * @return Activite
     */
    public function setOrganisateur(\moove\ActiviteBundle\Entity\Utilisateur $organisateur = null)
    {
        $this->organisateur = $organisateur;

        return $this;
    }
    
    
    

    /**
     * Get organisateur
     *
     * @return \moove\ActiviteBundle\Entity\Utilisateur 
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * Set niveauRequis
     *
     * @param \moove\ActiviteBundle\Entity\Niveau $niveauRequis
     * @return Activite
     */
    public function setNiveauRequis(\moove\ActiviteBundle\Entity\Niveau $niveauRequis = null)
    {
        $this->niveauRequis = $niveauRequis;

        return $this;
    }

    /**
     * Get niveauRequis
     *
     * @return \moove\ActiviteBundle\Entity\Niveau 
     */
    public function getNiveauRequis()
    {
        return $this->niveauRequis;
    }

    /**
     * Set sportPratique
     *
     * @param \moove\ActiviteBundle\Entity\Sport $sportPratique
     * @return Activite
     */
    public function setSportPratique(\moove\ActiviteBundle\Entity\Sport $sportPratique = null)
    {
        $this->sportPratique = $sportPratique;

        return $this;
    }

    /**
     * Get sportPratique
     *
     * @return \moove\ActiviteBundle\Entity\Sport 
     */
    public function getSportPratique()
    {
        return $this->sportPratique;
    }

    /**
     * Set lieuRDV
     *
     * @param \moove\ActiviteBundle\Entity\Lieu $lieuRDV
     * @return Activite
     */
    public function setLieuRDV(\moove\ActiviteBundle\Entity\Lieu $lieuRDV = null)
    {
        $this->lieuRDV = $lieuRDV;

        return $this;
    }

    /**
     * Get lieuRDV
     *
     * @return \moove\ActiviteBundle\Entity\Lieu 
     */
    public function getLieuRDV()
    {
        return $this->lieuRDV;
    }

    /**
     * Set lieuDepart
     *
     * @param \moove\ActiviteBundle\Entity\Lieu $lieuDepart
     * @return Activite
     */
    public function setLieuDepart(\moove\ActiviteBundle\Entity\Lieu $lieuDepart = null)
    {
        $this->lieuDepart = $lieuDepart;

        return $this;
    }

    /**
     * Get lieuDepart
     *
     * @return \moove\ActiviteBundle\Entity\Lieu 
     */
    public function getLieuDepart()
    {
        return $this->lieuDepart;
    }

    /**
     * Set lieuArrivee
     *
     * @param \moove\ActiviteBundle\Entity\Lieu $lieuArrivee
     * @return Activite
     */
    public function setLieuArrivee(\moove\ActiviteBundle\Entity\Lieu $lieuArrivee = null)
    {
        $this->lieuArrivee = $lieuArrivee;

        return $this;
    }

    /**
     * Get lieuArrivee
     *
     * @return \moove\ActiviteBundle\Entity\Lieu 
     */
    public function getLieuArrivee()
    {
        return $this->lieuArrivee;
    }
}
