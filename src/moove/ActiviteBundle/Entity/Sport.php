<?php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="moove\ActiviteBundle\Entity\SportRepository")
 */
class Sport
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="urlPictogramme", type="string", length=255)
     */
    private $urlPictogramme;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nomIcone", type="string", length=255)
     */
    private $nomIcone;    


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
     * @return Sport
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
     * Set urlPictogramme
     *
     * @param string $urlPictogramme
     * @return Sport
     */
    public function setUrlPictogramme($urlPictogramme)
    {
        $this->urlPictogramme = $urlPictogramme;

        return $this;
    }

    /**
     * Get urlPictogramme
     *
     * @return string 
     */
    public function getUrlPictogramme()
    {
        return "bundles/mooveactivite/images/sports/" . $this->urlPictogramme;

        return $this->urlPictogramme;
    }
    

    /**
     * Set nomIcone
     *
     * @param string $nomIcone
     * @return Sport
     */
    public function setNomIcone($nomIcone)
    {
        $this->nomIcone = $nomIcone;

        return $this;
    }

    /**
     * Get nomIcone
     *
     * @return string 
     */
    public function getNomIcone()
    {
        return $this->nomIcone;
    }
}
