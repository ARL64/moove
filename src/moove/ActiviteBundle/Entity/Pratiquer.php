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
     * @ORM\Column(name="idUtilisateur", type="integer")
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Utilisateur")
     * @ORM\Id
     */
    private $idUtilisateur;

    /**
     * @var integer
     *
     * @ORM\Column(name="idSport", type="integer")
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Sport")
     * @ORM\Id
     */
    private $idSport;

    /**
     * @var integer
     *
     * @ORM\Column(name="idNiveau", type="integer")
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Niveau")
     * @ORM\Id
     */
    private $idNiveau;

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     * @return Pratiquer
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
     * Set idSport
     *
     * @param integer $idSport
     * @return Pratiquer
     */
    public function setIdSport($idSport)
    {
        $this->idSport = $idSport;

        return $this;
    }

    /**
     * Get idSport
     *
     * @return integer 
     */
    public function getIdSport()
    {
        return $this->idSport;
    }

    /**
     * Set idNiveau
     *
     * @param integer $idNiveau
     * @return Pratiquer
     */
    public function setIdNiveau($idNiveau)
    {
        $this->idNiveau = $idNiveau;

        return $this;
    }

    /**
     * Get idNiveau
     *
     * @return integer 
     */
    public function getIdNiveau()
    {
        return $this->idNiveau;
    }
}
