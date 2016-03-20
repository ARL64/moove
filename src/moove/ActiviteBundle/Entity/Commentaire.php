<?php

namespace moove\ActiviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="moove\ActiviteBundle\Entity\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posteA", type="datetime")
     */
    private $posteA;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Activite")
     */
    private $activite;

    /**
     * @ORM\ManyToOne(targetEntity="moove\UtilisateurBundle\Entity\Utilisateur", inversedBy="commentaires")
     */
    private $utilisateur;
    
    
    
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
     * Set contenu
     *
     * @param string $contenu
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set posteA
     *
     * @param \DateTime $posteA
     * @return Commentaire
     */
    public function setPosteA($posteA)
    {
        $this->posteA = $posteA;

        return $this;
    }

    /**
     * Get posteA
     *
     * @return \DateTime 
     */
    public function getPosteA()
    {
        return $this->posteA;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Commentaire
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set activite
     *
     * @param \moove\ActiviteBundle\Entity\Activite $activite
     * @return Commentaire
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

    /**
     * Set utilisateur
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $utilisateur
     * @return Commentaire
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
}
