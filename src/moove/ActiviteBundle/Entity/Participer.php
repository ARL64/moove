<?php
// src/moove/ActiviteBundle/Entity/Participer.php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="moove\ActiviteBundle\Entity\ParticiperRepository")
 */
class Participer
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
     * @ORM\ManyToOne(targetEntity="moove\UtilisateurBundle\Entity\Utilisateur")
     */
    private $utilisateur;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Activite", inversedBy="participer")
     */
    private $activite;

    /**
     * @var smallint
     *
     * @ORM\Column(name="estAccepte", type="smallint")
     */
    private $estAccepte; // type : "smallint"


  

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
     * Set estAccepte
     *
     * @param integer $estAccepte
     * @return Participer
     */
    public function setEstAccepte($estAccepte)
    {
        $this->estAccepte = $estAccepte;

        return $this;
    }

    /**
     * Get estAccepte
     *
     * @return integer 
     */
    public function getEstAccepte()
    {
        return $this->estAccepte;
    }

    /**
     * Set utilisateur
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $utilisateur
     * @return Participer
     */
    public function setUtilisateur(\moove\UtilisateurBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \moove\UtilisateurBundle\Entity\Utilisateur 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set activite
     *
     * @param \moove\ActiviteBundle\Entity\Activite $activite
     * @return Participer
     */
    public function setActivite(\moove\ActiviteBundle\Entity\Activite $activite = null)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return \moove\ActiviteBundle\Entity\Activite 
     */
    public function getActivite()
    {
        return $this->activite;
    }
}
