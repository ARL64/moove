<?php

namespace moove\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use moove\ActiviteBundle\Entity\Activite;
use moove\ActiviteBundle\Entity\Sport;
use moove\ActiviteBundle\Entity\Lieu;
use Symfony\Component\HttpFoundation\Request;

class ActivitesController extends Controller
{
    // /!\ Actions métier
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/accueil"
     * 
     */
    public function tableauDeBordAction()
    {
        $this->checkAuthorization();
        //on récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        /** Repository de Participer */
        $repParticipations = $this->getRepository('Participer');
        /** Repository de Activite */
        $repActivite = $this->getRepository('Activite');

        $nbParticipations = count($repParticipations->findBy(array('utilisateur' => $utilisateur, 'estAccepte' => 1)));
        $nbOrganisations = count($repActivite->findByOrganisateur($utilisateur));
        $nbDemandesEnAttente = count($repParticipations->findBy(array('utilisateur' => $utilisateur, 'estAccepte' => 0)));
       
        $listeDesDemandesDeParticipationsEnAttente = $repParticipations->findByOrganisateur($utilisateur, 0);

        $nbDemandesParticipationsActiviteEnAttente = count($listeDesDemandesDeParticipationsEnAttente);
        
        $listeDesDemandesEnAttente = $repActivite->findByUtilisateurAccepter($utilisateur, 0);
        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordAccueil.html.twig', 
                            array(  'nbParticipations' => $nbParticipations, 
                                    'nbOrganisations' => $nbOrganisations, 
                                    'nbDemandesEnAttente' => $nbDemandesEnAttente, 
                                    'listeDemandesEnAttente' => $listeDesDemandesEnAttente,
                                    'nbDemandesEnAttenteOrganisateur' => $nbDemandesParticipationsActiviteEnAttente,
                                    'ListeDemandeAValide' => $listeDesDemandesDeParticipationsEnAttente));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activite/{idActivite}"
     * 
     */
    public function detailsActiviteAction($idActivite)
    {
        $this->checkAuthorization();
        /** Repository de Participer */
        $repParticiper = $this->getRepository('Participer');
        /** Repository de Activite */
        $repActivite = $this->getRepository('Activite');
        /** Repository de Utilisateur */
        $repUtilisateur = $this->getRepository('Utilisateur', 'Utilisateur');
        /** Repository de Pratiquer */
        $repPratiquer = $this->getRepository('Pratiquer');
        /** Repository de Niveau */
        $repNiveau = $this->getRepository('Niveau');
        
        // On récupère l'activité par l'id de l'activite $idActivite
        $activite = $repActivite->find($idActivite);
        
        $estParticipant = $this->estParticipantDeActivite($activite);
        $estAccepte = $this->estAccepte($activite);
        $estOrganisateur = $this->estOrganisateur($activite);
        
        $niveauOrganisateur = null;

        $resultatNiveauOrganisateur = $repNiveau->findByUtilisateur($activite->getOrganisateur(), $activite->getSportPratique());
        if(!is_null($resultatNiveauOrganisateur))
        {
           $resultatNiveauOrganisateur->getLibelle();
        }
         
         $arg = $estOrganisateur? array(1,0) : 1;   
         
        // On récupère un tableau d'objet Participer $tabParticiper
        $tabParticiper = $repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => $arg));
        // On récupère le nombre de participants de l'activité
        $nbParticipants = count($tabParticiper);
        
        // On indique si l'utilisateur accédant au détails de l'activité est participant ou non


        return $this->render('mooveActiviteBundle:Activite:detailsActivite.html.twig', array('activite' => $activite, 
                                                                                            'tabParticipants' => $tabParticiper,
                                                                                            'niveauOrganisateur' => $niveauOrganisateur,
                                                                                            'nbParticipants' => $nbParticipants,
                                                                                            'estParticipant' => $estParticipant,
                                                                                            'estAccepte' => $estAccepte,
                                                                                            'estOrganisateur' => $estOrganisateur));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/historique"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function historiqueAction()
    {   
        $this->checkAuthorization();
        
        $utilisateur = $this->getUser(); 
        $repActivite = $this->getRepository('Activite');
        $tabActivites = $repActivite->findByUtilisateur($utilisateur->getId(), true);
        /** Liste du nombre de participations */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig',
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activites"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function enCoursAction()
    {   
        $this->checkAuthorization();

        /** liste des activités de l'utilisateur courrant*/
        $utilisateur = $this->getUser(); 
        $repActivite = $this->getRepository('Activite');
        $tabActivites = $repActivite->findByUtilisateur($utilisateur->getId(), false);        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        $tabEstAccepte = [];
        foreach($tabActivites as $activite)
        {
            $tabEstAccepte[] = $this->estAccepte($activite);
        }
        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants, 'tabEstAccepte' => $tabEstAccepte));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/rechercher"
     * 
     */
    public function rechercherActiviteAction()
    {
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        // On récupère toutes les activités dans $tabActivites
        $tabActivites = $repActivite->findAll();
        // On compte combien il y a d'activités
        $nbActivites = count($tabActivites);
        
        return $this->render('mooveActiviteBundle:Activite:rechercherActivites.html.twig', array(
            'tabActivites' => $tabActivites,
            'nbActivites' => $nbActivites
        ));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/proposer"
     * 
     */
    public function proposerActiviteAction(Request $requeteUtilisateur)
    {
        // On crée un objet "activité"
        $activite = new Activite();
        $lieu = new Lieu();
        
        // On initialise l'organisteur avec l'utilisateur qui est entrain de créer l'activité
        $activite->setOrganisateur($this->getUser());

        // On crée le formulaire permettant de saisir un livre
        $formulaireActivite = $this->createFormBuilder($activite)
                                    ->add('sportPratique', 'entity',
                                          array('label' => 'Sport',
                                                'empty_value' => 'Sélectionnez un sport',
                                                'class' => 'mooveActiviteBundle:Sport',
                                                'property' => 'nom',
                                                'multiple' => false,
                                                'expanded' => false))
                                    ->add('niveauRequis', 'entity',
                                          array('label' => 'Niveau requis',
                                                'empty_value' => 'Sélectionez un niveau',
                                                'class' => 'mooveActiviteBundle:Niveau',
                                                'property' => 'libelle',
                                                'multiple' => false,
                                                'expanded' => false))
                                   //->add('lieuRDV')
                                   //->add('lieuDepart')
                                   //->add('lieuArrivee')
                                   ->add('dateHeureRDV', 'datetime', array('label' => 'Date et heure de rendez-vous'))
                                   ->add('dateFermeture', 'datetime', array('label' => 'Date et heure de fermeture  de l\'activité'))
                                   ->add('duree', 'time', array('label' => 'Durée estimée'))
                                   ->add('nbPlaces','integer', array('label'=> 'Nombre de places'))
                                   ->add('description', 'textarea' ,array ('label' => 'Informations'))
                                   ->getForm();
                                   
        /* On analyse la requête courante pour savoir si le formulaire a été soumis ou pas.
        Dans le cas d'une soumission, les données saisies par l'utilisateur viendront remplir
        l'objet $activite*/
        $formulaireActivite->handleRequest($requeteUtilisateur);
        
        if($formulaireActivite->isSubmitted()) // Le formulaire a été soumis
        {
            //On enregistre l'objet $livre en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($activite);
            $gestionnaireEntite->flush();
            
            //On redirige vers la page de visualisation de l'activité ajouté
            return $this->redirect($this->generateUrl('moove_activite_detailsActivite',
                                                      array('id' => $activite->get(id),'organisateur' => $organisateur)));
        }
        //A ce point, le visiteur arrive sur la page qui doit afficher le formulaire
        return $this->render('mooveActiviteBundle:Activite:proposerActivite.html.twig',
                             array('formulaireActivite' => $formulaireActivite->createView()));
    }
    // /!\ Fin actions métier
    
    
    
    
    
    
    
    
    
    // /!\ Fonction à partir d'ici 
    
    /**
     * Vérifie que l'utilisateur soit connecter. Si ce n'est pas le cas, il est re-dirigé
     */
    protected function checkAuthorization()
    {
        // Vérifie si l'utilisateur est authentifié 
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }   
    }
    
    /**
     * Retourne une liste du nombre de participant pour chaque activité fournis dans la $tabActivites.
     * 
     * @param $tabActivites (array<Activite>) Tableau contenant des activités
     * @return array<Integer>
     */
    protected function getNbParticipantsParActivite($tabActivites)
    {
        /** Repository de Participer */
        $repParticiper = $this->getRepository('Participer');

		/** Liste du nombre de participants */
        $listeNbParticipant = array();
	    foreach ($tabActivites as $activite) 
	    {
	        $listeParticipant = $repParticiper->findBy(array('activite' => $activite, 'estAccepte' => 1));
            $listeNbParticipant[] = count($listeParticipant);
        }
        
        return $listeNbParticipant;
    }

    /**
     * Retourne un booléen permettant d'indiquer si l'utilisateur connecté participe à l'activité $idActivite
     * 
     * @param $idActivite
     * @return boolean
     */
    protected function estParticipantDeActivite($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Participer
        $repParticiper = $this->getRepository('Participer');
        
        $listeParticipant = $repParticiper->findBy(array('activite' => $activite,
                                                         'utilisateur' => $utilisateur));
        return (!empty($listeParticipant));
    }
    
    /**
     * 
     * @param $activite (integer) id de l'activité souhaite
     * @return boolean true si l'utilisateur actuel est accepté, false sinon
     */
    protected function estAccepte($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Participer
        $repParticiper = $this->getRepository('Participer');
        
        $listeParticipant = $repParticiper->findOneBy(array('activite' => $activite,
                                                         'utilisateur' => $utilisateur));
                                                         
        // Si la variable est null, alors il n'y a aucune participation pour cette utilisateur à cette activité
        return is_null($listeParticipant)? 0 : $listeParticipant->getEstAccepte(); // 1 = accepter, 2 = refuser
        
        //return (!empty($listeParticipant));
    }
    
    protected function estOrganisateur($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');

        return ($repActivite->find($activite)->getOrganisateur() == $utilisateur);
    }
    
    /**
     * simplifie : $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:MonRepository');
     * en $this->getRepository('MonRepository');
     * 
     * @param $nomRepository (string) nom du repository souhaité
     * @param $nomBundle (string = 'Activite') nom du bundle (si différent de celui actuel)
     * @return (repository)
     */
    protected function getRepository($nomRepository, $nomBundle = 'Activite')
    {
        return $this->getDoctrine()->getManager()->getRepository('moove'.$nomBundle.'Bundle:'.$nomRepository);
    }
    
} // fin de "class ActivitesController extends Controller"