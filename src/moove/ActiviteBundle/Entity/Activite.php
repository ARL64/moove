<?php
// src/moove/ActiviteBundle/Entity/Activite.php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use moove\ActiviteBundle\Validator\Constraints as mooveAssert;

/**
 * Activite
 * 
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="moove\ActiviteBundle\Entity\ActiviteRepository")
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
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("today UTC+1")
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
     * @Assert\Range(
     *      min = 1,
     *      max = 500,
     *      minMessage = "Le nombre de places minimum autorisé est de {{ limit }}.",
     *      maxMessage = "Le nombre de places maximum autorisé est de {{ limit }}."
     * )
     */
    private $nbPlaces;

    /**
     * @var integer
     * 
     * @ORM\Column(name="nbParticipants", type="integer")
     */
    private $nbPartipants;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     * @Assert\DateTime
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateFermeture", type="datetime")
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("today UTC+1")
     */
    private $dateFermeture;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="estTerminee", type="boolean")
     */
    private $estTerminee;

    /**
     * @ORM\ManyToOne(targetEntity="moove\UtilisateurBundle\Entity\Utilisateur")
     */
    private $organisateur;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Niveau", cascade={"persist", "remove"})
     * @Assert\NotBlank
     */
    private $niveauRequis;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Sport", cascade={"persist", "remove"})
     * @Assert\NotBlank
     */
    private $sportPratique;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu", cascade={"persist", "remove"})
     */    
    private $lieuRDV;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu", cascade={"persist", "remove"})
     */
    private $lieuDepart;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu", cascade={"persist", "remove"})
     */
    private $lieuArrivee;
    
    /*
     * @mooveAssert\Adresse
     * @Assert\NotBlank(message = "L'adresse du lieu de rendez-vous est obligatoire.")
     */
    private $adresseLieuRDV;
    /*
     * @mooveAssert\Adresse
     */
    private $adresseLieuDepart;
    /*
     * @mooveAssert\Adresse
     */
    private $adresseLieuArrivee;

    /**
     * @ORM\OneToMany(targetEntity="moove\ActiviteBundle\Entity\Participer", mappedBy="activite")
     */
    private $participer;

    /**
     * @Assert\Callback
     * Permet de vérifier dans un même formualaire que la date de fermeture de l'activité se trouve avant la date de rendez-vous
     * Permet de vérifier que le nombre de participants d'une activité est inférieur ou égal au nombre de places totales de l'activité
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (($this->dateFermeture != '') && 
               ($this->dateHeureRDV < $this->dateFermeture) ) {

            $context->addViolationAt(
                'dateFermeture',
                'La date de fermeture n\'est pas valide. Elle doit être avant la date et heure de rendez-vous.',
                array(),
                null
            );

        }
        
        if($this->nbPartipants > $this->nbPlaces) {
            $context->addViolationAt(
                'nbParticipants',
                'L\'activité est déjà pleine, vous ne pouvez pas la rejoindre.',
                [],
                null
            );
        }
    }

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
     * Set nbParticipants
     *
     * @param integer $nbParticipants
     * @return Activite
     */
    public function setNbParticipants($nbPartipants)
    {
        $this->nbPartipants = $nbPartipants;

        return $this;
    }

    /**
     * Get nbPartipants
     *
     * @return integer 
     */
    public function getNbParticipants()
    {
        return $this->nbPartipants;
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
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $organisateur
     * @return Activite
     */
    public function setOrganisateur(\moove\UtilisateurBundle\Entity\Utilisateur $organisateur = null)
    {
        $this->organisateur = $organisateur;

        return $this;
    }
    
    
    

    /**
     * Get organisateur
     *
     * @return \moove\UtilisateurBundle\Entity\Utilisateur 
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
    
    
    
    public function tempsAvantDebut()
    {
        $dateNow = new \DateTime(/*'NOW'*/);
        return $dateNow->diff($this->dateHeureRDV);
    }
    
    /**
     * Get adresseLieuRDV
     *
     * @return string
     */
    public function getAdresseLieuRDV()
    {
        return $this->adresseLieuRDV;
    }

    /**
     * Set adresseLieuRDV
     *
     * @param string
     * @return Activite
     */
    public function setAdresseLieuRDV($adresseLieuRDV)
    {
        $this->adresseLieuRDV = $adresseLieuRDV;

        return $this;
    }
    
    /**
     * Get adresseLieuDepart
     *
     * @return string
     */
    public function getAdresseLieuDepart()
    {
        return $this->adresseLieuDepart;
    }

    /**
     * Set adresseLieuDepart
     *
     * @param string
     * @return Activite
     */
    public function setAdresseLieuDepart($adresseLieuDepart)
    {
        $this->adresseLieuDepart = $adresseLieuDepart;

        return $this;
    }
    
    /**
     * Get adresseLieuArrivee
     *
     * @return string
     */
    public function getAdresseLieuArrivee()
    {
        return $this->adresseLieuArrivee;
    }

    /**
     * Set adresseLieuArrivee
     *
     * @param string
     * @return Activite
     */
    public function setAdresseLieuArrivee($adresseLieuArrivee)
    {
        $this->adresseLieuArrivee = $adresseLieuArrivee;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participer = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Get participer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticiper()
    {
        return $this->participer;
    }

    /**
     * Add participer
     *
     * @param \moove\ActiviteBundle\Entity\Participer $participer
     * @return Activite
     */
    public function addParticiper(\moove\ActiviteBundle\Entity\Participer $participer)
    {
        $this->participer[] = $participer;

        return $this;
    }

    /**
     * Remove participer
     *
     * @param \moove\ActiviteBundle\Entity\Participer $participer
     */
    public function removeParticiper(\moove\ActiviteBundle\Entity\Participer $participer)
    {
        $this->participer->removeElement($participer);
    }
    
    public function __toString()
    {
       return (isset($this->dateHeureRDV))? 'Activité du ' . $this->dateHeureRDV->format('d/m/Y') : null;
    }
}
