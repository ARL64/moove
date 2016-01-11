<?php

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
     * @ORM\Id
     * @ORM\Column(name="idUtilisateur", type="integer")
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Utilisateur")
     */
    private $idUtilisateur;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="idActivite", type="integer")
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Activite")
     */
    private $idActivite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estAccepte", type="boolean")
     */
    private $estAccepte;


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
     * @param boolean $estAccepte
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
     * @return boolean 
     */
    public function getEstAccepte()
    {
        return $this->estAccepte;
    }

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     * @return Participer
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    /**
     * Get idUtilisateur
     *
     * @return integer 
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * Set idActivite
     *
     * @param integer $idActivite
     * @return Participer
     */
    public function setIdActivite($idActivite)
    {
        $this->idActivite = $idActivite;

        return $this;
    }

    /**
     * Get idActivite
     *
     * @return integer 
     */
    public function getIdActivite()
    {
        return $this->idActivite;
    }
}
