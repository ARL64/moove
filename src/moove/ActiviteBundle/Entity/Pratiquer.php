<?php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pratiquer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="moove\ActiviteBundle\Entity\PratiquerRepository")
 */
class Pratiquer
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
     * 
     * @ORM\ManyToOne(targetEntity="moove\UtilisateurBundle\Entity\Utilisateur", inversedBy="pratiquer")
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Sport")
     */
    private $sport;

    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Niveau")
     */
    private $niveau;

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
     * Set utilisateur
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $utilisateur
     * @return Pratiquer
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
     * Set sport
     *
     * @param \moove\ActiviteBundle\Entity\Sport $sport
     * @return Pratiquer
     */
    public function setSport(\moove\ActiviteBundle\Entity\Sport $sport = null)
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return \moove\ActiviteBundle\Entity\Sport 
     */
    public function getSport()
    {
        return $this->sport;
    }

    /**
     * Set niveau
     *
     * @param \moove\ActiviteBundle\Entity\Niveau $niveau
     * @return Pratiquer
     */
    public function setNiveau(\moove\ActiviteBundle\Entity\Niveau $niveau = null)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return \moove\ActiviteBundle\Entity\Niveau 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
}
