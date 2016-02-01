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
        //On récupère le manager & le repository des participations
        $repParticipations = $this->getRepository('Participer');

        $nbParticipations = $this->nbParticipations();
        $nbOrganisations = $this->nbOrganisations();
        $nbDemandesEnAttente = $this->nbDemandesEnAttente();
       
        $nbDemandesParticipationsActiviteEnAttente = count($repParticipations->findByOrganisateur($utilisateur, false));
        
        $listeDesDemandesEnAttente = $this->listeDesDemandesDeParticipations();
        $listeDesDemandesDeParticipationsEnAttente = $this->listeDesDemandesDeParticipationsAMesActivites();
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
        //$repUtilisateur = $this->getDoctrine()->getManager()->getRepository('mooveUtilisateurBundle:Utilisateur');
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
            
        // On récupère un tableau d'objet Participer $tabParticiper
        if($estOrganisateur)
            $arg = array(1,0);
        else
            $arg = 1;
        
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

        /** Liste des activitées finies, 20 résultats par page */   
        $tabActivites = $this->getListeParticipationForUser(true);
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
        $tabActivites = $this->getListeParticipationForUser(false);
        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
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
        $organisateur = $this->getUser();

        // On crée le formulaire permettant de saisir un livre
        $formulaireActivite = $this->createFormBuilder($activite)
                                /*->add('sportPratique', 
                                        'choice',
                                        /*['choices' => [
                                            new Sport('Ski'),
                                            new Sport('Randonnee'),
                                            new Sport('Cyclisme'),
                                            new Sport('Jogging')],
                                            'choice_label' => function($sportPratique) 
                                            {
                                                return strtoupper($sportPratique->getNom());
                                            },
                                            'choice_attr' => function($sportPratique) 
                                            {
                                                return ['class' => 'sportPratique'.$sportPratique->getNom()];
                                            }
                                        ]
                                    )*/
                                   
                                  /*array(
                                    'Ski' => ,
                                    'Randonnée' => ,
                                    'Cyclisme' => ,
                                    'Jogging' => )
                                    */
                                   //->add('ville')
                                   //->add('lieuRDV')
                                   //->add('lieuDepart')
                                   //->add('lieuArrivee')
                                   ->add('dateHeureRDV', 'datetime', array('label' => 'Date et heure de rendez-vous'))
                                   ->add('dateFermeture', 'datetime',array('label' => 'Date et heure de fermeture  de l\'activité'))
                                   ->add('duree', 'time',array('label' => 'Durée estimée'))
                                   ->add('nbPlaces','choice', array( 'choices'  => array(
                                       '1' => 1,
                                       '2' => 2,
                                       '3' => 3,
                                       '4' => 4,
                                       '5' => 5,
                                       '6' => 6,
                                       '7' => 7,
                                       '8' => 8,
                                       '9' => 9,
                                       '10' => 10,
                                       '11' => 11,
                                       '12' => 12),
                                       'label'=> 'Nombre de places'))
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
     * Retourne la liste des Activité auquel participe l'utilisateur actuellement autentifié.
     * 
     * @param $terminer (boolean) indique si les activités son finis (true) ou non (false) 
     * @param $page (integer&) indique la page actuel de recherche (fonctionne de pair avec $nbResultatPage). La valeur pourra être adapter si elle est trop grande.
     * @param $nbResultatPage (integer) indique le nombre de résultat a afficher par $page
     * @param $nbPage (&integer) variable de stockage pour le nombre de page nécessaire a l'affichage de toute les activités
     * @return array<Activite>
     */
    protected function getListeParticipationForUser($terminer) // $terminer, &$page, $nbResultatPage, &$nbPage
    {
        // On obtient l'utilisateur courrant
        $utilisateur = $this->getUser(); 

        // On récupère le manager & le repository des Participants : Participer
	//	$repParticiper = $this->getRepository('Participer');
		
		// On récupère le manager & le repository des Activités : Activite
		$repActivite = $this->getRepository('Activite');
/*
		// On récupère la liste des Participations liés à la personne connectée $utilisateur
		$listeEntiereParticiper = $repParticiper->findBy(array('idUtilisateur' => $utilisateur));
		
		// optimisable
		// on récupère la liste des ID des activité
		$listeIdActivite = array();
	    foreach ($listeEntiereParticiper as $participe) 
	    {
            $listeIdActivite[] = $participe->getIdActivite();
        }
        
        // arrondi a l'entier supérieur du total de page nécessaire
        $nbPage = ceil(count($repActivite->findBy(array('id'=>$listeIdActivite, 'estTerminee' => $terminer))) / $nbResultatPage);
        // si l'utilisateur n'as aucune activité (évite les bug de requête avec -X)
        if($nbPage == 0) $nbPage = 1;
        // si l'utilisateur rentre manuellement une valeur impossible
        if($page > $nbPage) $page = $nbPage;*/
            
        // on retourne la liste des activités, terminées ou non ($terminer), triées par ordre croissant selon la date,
        // avec une limite de $nbResultatPage activités
		/*return $repActivite->findBy(array('id'=>$listeIdActivite, 'estTerminee' => $terminer), 
		                            array('dateHeureRDV' => 'DESC'), 
		                            $nbResultatPage, 
		                            ($page-1)*$nbResultatPage);*/
	    return $repActivite->findByUtilisateur($utilisateur->getId(), $terminer);
    }
    
    protected function nbParticipations()
    {
        //On récupère l'utilisateur actuel
        $utilisateur = $this->getUser();
        
        // On récupère le manager & le repository des Participants : Participer
		$repParticiper = $this->getRepository('Participer');
		
		// On compte le nombre de participation d'un utilisateur 
		$nombreParticipations = count($repParticiper->findBy(array('utilisateur' => $utilisateur, 'estAccepte' => 1)));
		
		//On renvoie le nombre de participation d'un utilisateur
        return $nombreParticipations;
    }
    
    /**
     * Retourne le nombre d'activité organisé par l'utilisateur
     * 
     * @return integer
     */
    protected function nbOrganisations()
    {
        //on récupère l'utilisateur connecté
         $utilisateur = $this->getUser();

        // On récupère le manager & le repository des Activtés
		$repActivite = $this->getRepository('Activite');
		
		// On compte le nombre d'activité dont on est organisateur
		$nombreOrganisations = count($repActivite->findBy(array('organisateur' => $utilisateur)));
		
		//On renvoie à la vue le nombre d'activité dont on est organisateur
        return $nombreOrganisations;
    }
   
    /**
     * Retourne le nombre d'activité ou une demande d'admission est en cours pour l'utilisateur
     * 
     * @return integer
     */ 
    protected function nbDemandesEnAttente()
    {
        //on récupère l'id de l'utilisateur
        $utilisateur = $this->getUser();
        
        //On récupère le manager & le repository des participations
        $repParticipations = $this->getRepository('Participer');
        
        // On récupère la liste des Participations liés à la personne connectée $utilisateur
		$nbDemandesEnAttente = count($repParticipations->findBy(array('utilisateur' => $utilisateur, 'estAccepte' => 0)));
		
		//On renvoie le nombre de demandes en attente
		return $nbDemandesEnAttente;
    }

    
    /**
     * retourne un tableau contenant le nombre de demande et le tableau de toute les activités
     * 
     * @return array<integer, array<activite>>
     */
    protected function listeDesDemandesDeParticipations()
    {
        //On récupère l'utilisateur
        $utilisateur = $this->getUser()->getId();
        
        // On récupère le nombre de demandes en attente
        $nbDemandesEnAttente = $this->nbDemandesEnAttente();
        
        //On récupère le manager & repository des participations
        $repParticipations = $this->getRepository('Participer');
        
         //On récupère le manager & le repository des activités
        $repActivite = $this->getRepository('Activite');
        
        // On récupère la liste des Participations liés à la personne connectée $utilisateur
		$tabParticipationEnAttente = $repParticipations->findBy(array('utilisateur' => $utilisateur, 'estAccepte' => 0));
		
		

        $listeActivite = array();
	    foreach ($tabParticipationEnAttente as $participation) 
	    {
	        $activite = $repActivite->find($participation->getActivite());
	        
	        $listeActivite[] = $activite;

        }
        $tabDemandesEnAttente = array('nbDemandes' => $nbDemandesEnAttente, 'listeDemande' => $listeActivite);
        return $tabDemandesEnAttente;
    }
    
    /**
     * retourne un tableau contenant le nombre de demande à mes activités que je propose et le tableau de toute les activités
     * 
     * @return array<integer, array<Activite>>
     */   
    protected function listeDesDemandesDeParticipationsAMesActivites()
    {
		//On récupère l'utilisateur
        $organisateur = $this->getUser();
        
        //On récupère le manager & le repository des activités & des utilisateurs
        $repActivite = $this->getRepository('Activite');
		$repUtilisateurs = $this->getDoctrine()->getManager()->getRepository('mooveUtilisateurBundle:Utilisateur');
		$repParticipations = $this->getRepository('Participer');

		//On crée un tableau contenant toutes les activités dont je suis organisateur
        $listeActivitesOrganisateur = $repActivite->findBy(array('organisateur' => $organisateur));

		$listeUtilisateurParticipants = array();
		$listeActiviteParticipants = array();
		$listeParticipants = array();
		foreach($listeActivitesOrganisateur as $activite)
        {
            $listeParticipations = $repParticipations->findBy(array( 'activite' => $activite->getId(), 'estAccepte' => 0));
            //on récupère la liste des participations où le booléen est à faux
            if($listeParticipations != null)
            {
                foreach($listeParticipations as $participation)
                {
                    $listeParticipants[] = $participation;
			        $listeUtilisateurParticipants[] = $repUtilisateurs->find($participation->getUtilisateur());
			        $listeActiviteParticipants[] = $repActivite->find($participation->getActivite());
                }
                
            }

			
        }

		return array('listeDemandes' => $listeParticipants, 'listeUtilisateur' => $listeUtilisateurParticipants, 'listeActivite' => $listeActiviteParticipants);
		 
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
        if(is_null($listeParticipant))
            return 0;
        else
            return $listeParticipant->getEstAccepte(); // 1 = accepter, 2 = refuser
        
        
        //return (!empty($listeParticipant));
    }
    
    protected function estOrganisateur($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        $organisateur = $repActivite->find($activite)->getOrganisateur();
        
        return ($organisateur == $utilisateur);
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