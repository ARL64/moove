<?php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lieu
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="moove\ActiviteBundle\Entity\LieuRepository")
 */
class Lieu
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="numeroRue", type="integer", nullable=true)
     */
    private $numeroRue;

    /**
     * @var string
     *
     * @ORM\Column(name="nomRue", type="string", length=255, nullable=true)
     */
    private $nomRue;

    /**
     * @var string
     *
     * @ORM\Column(name="complementAdresse", type="string", length=255, nullable=true)
     */
    private $complementAdresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="codePostal", type="integer", nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=18,scale=14, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=18,scale=14, nullable=true)
     */
    private $longitude;


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
     * @return Lieu
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
     * Set numeroRue
     *
     * @param integer $numeroRue
     * @return Lieu
     */
    public function setNumeroRue($numeroRue)
    {
        $this->numeroRue = $numeroRue;

        return $this;
    }

    /**
     * Get numeroRue
     *
     * @return integer 
     */
    public function getNumeroRue()
    {
        return $this->numeroRue;
    }

    /**
     * Set nomRue
     *
     * @param string $nomRue
     * @return Lieu
     */
    public function setNomRue($nomRue)
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    /**
     * Get nomRue
     *
     * @return string 
     */
    public function getNomRue()
    {
        return $this->nomRue;
    }

    /**
     * Set complementAdresse
     *
     * @param string $complementAdresse
     * @return Lieu
     */
    public function setComplementAdresse($complementAdresse)
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }

    /**
     * Get complementAdresse
     *
     * @return string 
     */
    public function getComplementAdresse()
    {
        return $this->complementAdresse;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     * @return Lieu
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Lieu
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Lieu
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Lieu
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
