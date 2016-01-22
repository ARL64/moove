<?php
// src/moove/UtilisateurBundle/Entity/Utilisateur.php

namespace moove\UtilisateurBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class Utilisateur extends BaseUser
{
    /**
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="URLAvatar", type="string", length=255, nullable=true)
     */
    private $URLAvatar;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu")
     */
    private $lieuResidence;
    
    /* /!\ Début des get & set /!\ */
     /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateNaissance", type="datetime", nullable=true)
     */
    private $dateNaissance;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set nom
     *
     * @param string $nom
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set URLAvatar
     *
     * @param string $uRLAvatar
     * @return Utilisateur
     */
    public function setURLAvatar($uRLAvatar)
    {
        $this->URLAvatar = $uRLAvatar;

        return $this;
    }

    /**
     * Get URLAvatar
     *
     * @return string 
     */
    public function getURLAvatar()
    {
        return $this->URLAvatar;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Utilisateur
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set lieuResidence
     *
     * @param \moove\ActiviteBundle\Entity\Lieu $lieuResidence
     * @return Utilisateur
     */
    public function setLieuResidence(\moove\ActiviteBundle\Entity\Lieu $lieuResidence = null)
    {
        $this->lieuResidence = $lieuResidence;

        return $this;
    }

    /**
     * Get lieuResidence
     *
     * @return \moove\ActiviteBundle\Entity\Lieu 
     */
    public function getLieuResidence()
    {
        return $this->lieuResidence;
    }
}
