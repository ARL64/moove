<?php

namespace moove\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $nbParticipations = $this->nbParticipations();
        $nbOrganisations = $this->nbOrganisations();
        $nbDemandesEnAttente = $this->nbDemandesEnAttente();
        $nbDemandesParticipationsActiviteEnAttente = $this->nbDemandesParticipationsActiviteEnAttente();
        $listeDesDemandesEnAttente = $this->listeDesDemandesDeParticipations();
        $listeDesDemandesDeParticipationsEnAttente = $this->listeDesDemandesDeParticipationsAMesActivites();
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordAccueil.html.twig', 
                            array(  'nbParticipations' => $nbParticipations, 
                                    'nbOrganisations' => $nbOrganisations, 
                                    'nbDemandesEnAttente' => $nbDemandesEnAttente, 
                                    'listeDemandesEnAttente' => $listeDesDemandesEnAttente,
                                    'listeDemandesEnAttenteOrganisateur' => $nbDemandesParticipationsActiviteEnAttente,
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
        $repUtilisateur = $this->getRepository('Utilisateur');
        /** Repository de Pratiquer */
        $repPratiquer = $this->getRepository('Pratiquer');
        /** Repository de Niveau */
        $repNiveau = $this->getRepository('Niveau');
        
        // On récupère l'activité par l'id de l'activite $idActivite
        $activite = $repActivite->find($idActivite);
        
        // On récupère le un tableau composé d'un objet Pratiquer
        $tabPratiquer = $repPratiquer->findBy(array('idUtilisateur' => $activite->getOrganisateur()->getId(),
                                                  'idSport' => $activite->getSportPratique()->getId(),
                                            ));
        // On récupère le niveau de l'organisateur ayant créé l'activité
        $niveauOrganisateur = $repNiveau->find($tabPratiquer[0]->getIdNiveau())->getLibelle();
        // On récupère un tableau d'objet Participer $tabParticiper
        $tabParticiper = $repParticiper->findBy(array('idActivite' => $idActivite, 'estAccepte' => true));
        // On initialise le tableau des objets Utilisateur
        $tabParticipants = [];
        // Pour chaque objet Participer on récupère l'utilisateur dans $tabParticipants
        foreach ($tabParticiper as $participer) {
            // On met dans le tableau des participants les utilisateurs
            $tabParticipants[] = $repUtilisateur->find($participer->getIdUtilisateur());
        }
        
        // On récupère le nombre de participants de l'activité
        $nbParticipants = count($tabParticipants);
        
        // On indique si l'utilisateur accédant au détails de l'activité est participant ou non
        $estParticipant = $this->estParticipantDeActivite($activite);
        $estAccepte = $this->estAccepte($activite);
        $estOrganisateur = $this->estOrganisateur($activite);

        return $this->render('mooveActiviteBundle:Activite:detailsActivite.html.twig', array('activite' => $activite, 
                                                                                            'tabParticipants' => $tabParticipants,
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
    public function historiqueAction($page)
    {   
        $this->checkAuthorization();
        /** Entier définissant le nombre de résultat par page */
        $nbRsultatParPage = 2;
        /** Entier définissant le nombre de page nécessaire a l'affichage de tout les résultats. */
        $nbPage;
        /** Liste des activitées finies, 20 résultats par page */   
        $tabActivites = $this->getListeParticipationForUser(true, $page, $nbRsultatParPage, $nbPage);
        /** Liste du nombre de participations */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig',
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants, 'page'=>$page, 'nbPage'=>$nbPage));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activites/{page}"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function enCoursAction($page)
    {   
        $this->checkAuthorization();
        /** Entier définissant le nombre de résultat par page */
        $nbRsultatParPage = 20;
        /** Entier définissant le nombre de page nécessaire a l'affichage de tout les résultats. */
        $nbPage;
        /** liste des activités de l'utilisateur courrant*/
        $tabActivites = $this->getListeParticipationForUser(false, $page, $nbRsultatParPage, $nbPage);
        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        $tabEstAccepte = [];
        foreach($tabActivites as $activite)
        {
            $tabEstAccepte[] = $this->estAccepte($activite);
        }
        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants, 'page'=>$page, 'nbPage'=>$nbPage,
                                'tabEstAccepte' => $tabEstAccepte));
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
     * simplifie : $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:MonRepository');
     * en $this->getRepository('MonRepository');
     * 
     * @param $nomRepository (string) nom du repository souhaité
     * @return (repository)
     */
    protected function getRepository($nomRepository)
    {
        return $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:'.$nomRepository);
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
	        $listeParticipant = $repParticiper->findByIdActivite($activite->getId());
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
    protected function getListeParticipationForUser($terminer, &$page, $nbResultatPage, &$nbPage)
    {
        // On obtient l'utilisateur courrant
        $utilisateur = $this->getUser(); 

        // On récupère le manager & le repository des Participants : Participer
		$repParticiper = $this->getRepository('Participer');
		// On récupère le manager & le repository des Activités : Activite
		$repActivite = $this->getRepository('Activite');

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
        if($page > $nbPage) $page = $nbPage;
            
        // on retourne la liste des activités, terminées ou non ($terminer), triées par ordre croissant selon la date,
        // avec une limite de $nbResultatPage activités
		/*return $repActivite->findBy(array('id'=>$listeIdActivite, 'estTerminee' => $terminer), 
		                            array('dateHeureRDV' => 'DESC'), 
		                            $nbResultatPage, 
		                            ($page-1)*$nbResultatPage);*/
		                            
	    return $repActivite->findByUtilisateur($utilisateur->getId());
    }
    
    protected function nbParticipations()
    {
        //On récupère l'utilisateur actuel
        $idUtilisateur = $this->getUser();
        
        // On récupère le manager & le repository des Participants : Participer
		$repParticiper = $this->getRepository('Participer');
		
		// On compte le nombre de participation d'un utilisateur 
		$nombreParticipations = count($repParticiper->findBy(array('idUtilisateur' => $idUtilisateur)));
		
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
        $utilisateur = $this->getUser()->getId();
        
        //On récupère le manager & le repository des participations
        $repParticipations = $this->getRepository('Participer');
        
        // On récupère la liste des Participations liés à la personne connectée $utilisateur
		$nbDemandesEnAttente = count($repParticipations->findBy(array('idUtilisateur' => $utilisateur, 'estAccepte' => false)));
		
		//On renvoie le nombre de demandes en attente
		return $nbDemandesEnAttente;
    }
    
    /**
     * Retourne le nombre de participant qu'il reste a accepté a dans l'activité dont l'utilisateur est l'organisateur
     * 
     * @return integer
     */
    protected function nbDemandesParticipationsActiviteEnAttente()
    {
        //on récupère l'utilisateur connecté
        $organisateur = $this->getUser();
        
        //On récupère le manager & le repository des activités
        $repActivite = $this->getRepository('Activite');
        
        //On crée un tableau contenant toutes les activités dont je suis organisateur
        $listActivitesOrganisateur = $repActivite->findBy(array('organisateur' => $organisateur));
        
        //On récupère le manager & le repository des participations
        $repParticipations = $this->getRepository('Participer');
        
        /*On initialise la variable que l'on renverra contenant le nombre de personnes qui ont demandé à rejoindre une activité 
        mais que l'organisateur n'a pas encore accepté*/
        $nbDemandesParticipationsActiviteEnAttente = 0;
        
        $listeNbParticipant = array();

        //On parcourt le tableau contenant toutes les activités de l'utilisateur en tant qu'organisateur
        foreach($listActivitesOrganisateur as $activite)
        {
            //On récupère l'id de l'activité en cours
            $idActivite = $activite->getId();
            
            //on compte le nombre de fois où l'on retrouve estAccepte = false dans l'activité en cours
            $listeNbParticipant[] = count($repParticipations->findBy(array('idActivite' => $idActivite, 'estAccepte' => false)));
            
        }
        
        foreach($listeNbParticipant as $i)
        {
            $nbDemandesParticipationsActiviteEnAttente += $i;
        }
        
        /*Après avoir parcouru l'ensemble des activités de l'orgnisateur la variable $nbDemandesParticipationsActiviteEnAttente
        contient le nombre total des demandes de participatin encore en attente toute activité de l'organisateur confondu.*/
        return $nbDemandesParticipationsActiviteEnAttente;
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
		$tabParticipationEnAttente = $repParticipations->findBy(array('idUtilisateur' => $utilisateur, 'estAccepte' => false));
		
		

        $listeActivite = array();
	    foreach ($tabParticipationEnAttente as $participation) 
	    {
	        $activite = $repActivite->find($participation->getIdActivite());
	        
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
		$repUtilisateurs = $this->getRepository('Utilisateur');
		$repParticipations = $this->getRepository('Participer');

		//On crée un tableau contenant toutes les activités dont je suis organisateur
        $listeActivitesOrganisateur = $repActivite->findBy(array('organisateur' => $organisateur));

		$listeUtilisateurParticipants = array();
		$listeActiviteParticipants = array();
		$listeParticipants = array();
		foreach($listeActivitesOrganisateur as $activite)
        {
            $listeParticipations = $repParticipations->findBy(array( 'idActivite' => $activite->getId(), 'estAccepte' => false));
            //on récupère la liste des participations où le booléen est à faux
            if($listeParticipations != null)
            {
                foreach($listeParticipations as $participation)
                {
                    $listeParticipants[] = $participation;
			        $listeUtilisateurParticipants[] = $repUtilisateurs->find($participation->getIdUtilisateur());
			        $listeActiviteParticipants[] = $repActivite->find($participation->getIdActivite());
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
        
        $listeParticipant = $repParticiper->findBy(array('idActivite' => $activite,
                                                         'idUtilisateur' => $utilisateur));
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
        
        $listeParticipant = $repParticiper->findBy(array('idActivite' => $activite,
                                                         'idUtilisateur' => $utilisateur,
                                                         'estAccepte' => true));
        return (!empty($listeParticipant));
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
    
    
    
} // fin de "class ActivitesController extends Controller"