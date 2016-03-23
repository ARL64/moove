<?php
// src/moove/UtilisateurBundle/Entity/Utilisateur.php

namespace moove\UtilisateurBundle\Entity;

use moove\ActiviteBundle\Entity\Lieu;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use moove\UtilisateurBundle\Validator\Constraints as mooveAssert;

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
     * @Assert\NotBlank(message = "Votre nom doit être spécifié.")
     * @Assert\Length(min = "2", 
     *                max = "50",
     *                minMessage = "Votre nom doit comporter au moins {{ limit }} caractères.",
     *                maxMessage = "Votre nom ne peut comporter que {{ limit }} caractères au maximum."
     * )
     */
    private $nom;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Votre prénom doit être spécifié.")
     * @Assert\Length(min = "3", 
     *                max = "50",
     *                minMessage = "Votre prénom doit comporter au moins {{ limit }} caractères.",
     *                maxMessage = "Votre prénom ne peut comporter que {{ limit }} caractères au maximum."
     * )
     */
    private $prenom;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="URLAvatar", type="string", length=255, nullable=true)
     */
    private $URLAvatar;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="sexe", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Veuillez sélectionner un genre.")
     * @Assert\Choice(choices = {"homme", "femme"},
     *                message = "Le sexe sélectionné n'est pas valide.")
     */
    private $sexe;
    
    /**
     * @ORM\ManyToOne(targetEntity="moove\ActiviteBundle\Entity\Lieu",
     *                cascade={"persist", "remove"})
     */
    private $lieuResidence;
    
    private $adresseLieuResidence;
   
    /**
     * @ORM\OneToMany(targetEntity="moove\ActiviteBundle\Entity\Pratiquer", mappedBy="utilisateur",
     *                cascade={"remove"})
     */
    private $pratiquer;
    
    /**
     * @ORM\OneToMany(targetEntity="moove\ActiviteBundle\Entity\Commentaire", mappedBy="utilisateur",
     *                cascade={"remove"})
     */
    private $commentaires;
    
    /* /!\ Début des get & set /!\ */
     /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dateNaissance", type="datetime", nullable=true)
     * @Assert\NotNull(message = "Veuillez indiquer votre date de naissance.")
     * @mooveAssert\Age
     */
    private $dateNaissance;
    
    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur", mappedBy="mesAmis")
     */
    private $amisAvecMoi;

    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur", inversedBy="amisAvecMoi")
     * @ORM\JoinTable(name="amisUtilisateur",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")}
     *      )
     */
    private $mesAmis;
    
    
    
    
    
    
    private $photo;


    public function __construct()
    {
        parent::__construct();
        $this->URLAvatar = 'default.png';
        $this->lieuResidence = new Lieu();
        $this->lieuResidence->setNom("")
                            ->setNumeroRue(null)
                            ->setNomRue("")
                            ->setComplementAdresse("")
                            ->setCodePostal(null)
                            ->setVille("")
                            ->setLatitude(null)
                            ->setLongitude(null)
                            ;
                            
        //$this->pratiquer = new ArrayCollection();
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
        return "bundles/mooveutilisateur/images/avatars/" . $this->URLAvatar;
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

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return Utilisateur
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }



    /**
     * Add pratiquer
     *
     * @param \moove\ActiviteBundle\Entity\Pratiquer $pratiquer
     * @return Utilisateur
     */
    public function addPratiquer(\moove\ActiviteBundle\Entity\Pratiquer $pratiquer)
    {
        $this->pratiquer[] = $pratiquer;

        return $this;
    }

    /**
     * Remove pratiquer
     *
     * @param \moove\ActiviteBundle\Entity\Pratiquer $pratiquer
     */
    public function removePratiquer(\moove\ActiviteBundle\Entity\Pratiquer $pratiquer)
    {
        $this->pratiquer->removeElement($pratiquer);
    }

    /**
     * Get pratiquer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPratiquer()
    {
        return $this->pratiquer;
    }
    
    /**
     * Get photo
     *
     * @return file 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set photo
     *
     * @param file $photo
     * @return Utilisateur
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }
    
    /**
     * Get adresseLieuResidence
     *
     * @return string
     */
    public function getAdresseLieuResidence()
    {
        return $this->adresseLieuResidence;
    }

    /**
     * Set adresseLieuArrivee
     *
     * @param string
     * @return Activite
     */
    public function setAdresseLieuResidence($adresseLieuResidence)
    {
        $this->adresseLieuResidence = $adresseLieuResidence;

        return $this;
    }
    
    public function __toString()
    {
       return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Add commentaires
     *
     * @param \moove\ActiviteBundle\Entity\Commentaire $commentaires
     * @return Utilisateur
     */
    public function addCommentaire(\moove\ActiviteBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;

        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \moove\ActiviteBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\moove\ActiviteBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add amisAvecMoi
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $amisAvecMoi
     * @return Utilisateur
     */
    public function addAmisAvecMoi(\moove\UtilisateurBundle\Entity\Utilisateur $amisAvecMoi)
    {
        $this->amisAvecMoi[] = $amisAvecMoi;

        return $this;
    }

    /**
     * Remove amisAvecMoi
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $amisAvecMoi
     */
    public function removeAmisAvecMoi(\moove\UtilisateurBundle\Entity\Utilisateur $amisAvecMoi)
    {
        $this->amisAvecMoi->removeElement($amisAvecMoi);
    }

    /**
     * Get amisAvecMoi
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAmisAvecMoi()
    {
        return $this->amisAvecMoi;
    }

    /**
     * Add mesAmis
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $mesAmis
     * @return Utilisateur
     */
    public function addMesAmi(\moove\UtilisateurBundle\Entity\Utilisateur $mesAmis)
    {
        $this->mesAmis[] = $mesAmis;

        return $this;
    }

    /**
     * Remove mesAmis
     *
     * @param \moove\UtilisateurBundle\Entity\Utilisateur $mesAmis
     */
    public function removeMesAmi(\moove\UtilisateurBundle\Entity\Utilisateur $mesAmis)
    {
        $this->mesAmis->removeElement($mesAmis);
    }

    /**
     * Get mesAmis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMesAmis()
    {
        return $this->mesAmis;
    }
}
