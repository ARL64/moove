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
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordAccueil.html.twig');
    }
    
    public function detailsActiviteAction($idActivite)
    {
        /** Repository de Participer */
        $repParticiper = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
        /** Repository de Activite */
        $repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');
        /** Repository de Utilisateur */
        $repUtilisateur = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Utilisateur');
        // On récupère l'activité par l'id de l'activite $idActivite
        $activite = $repActivite->find($idActivite);
        // On récupère un tableau d'objet Participer $tabParticiper
        $tabParticiper = $repParticiper->findByIdActivite($idActivite);
        // On initialise le tableau des objets Utilisateur
        $tabParticipants = [];
        // Pour chaque objet Participer on récupère l'utilisateur dans $tabParticipants
        foreach ($tabParticiper as $participer) {
            // On met dans le tableau des participants les utilisateurs
            $tabParticipants[] = $repUtilisateur->find($participer->getIdUtilisateur());
        }
        return $this->render('mooveActiviteBundle:Activite:detailsActivite.html.twig', array('activite' => $activite, 'tabParticipants' => $tabParticipants));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/historique"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function historiqueAction($page)
    {   
        /** Liste des activitées finies, 20 résultats par page */   
        $tabActivites = $this->getListeParticipationForUser(true, $page, 20);
        /** Liste du nombre de participations */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig',
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activites/{page}"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function enCoursAction($page)
    {   
        /** liste des activités de l'utilisateur courrant*/
        $tabActivites = $this->getListeParticipationForUser(false, $page, 20); 
        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants));
    }
    // /!\ Fin actions métier
    
    // /!\ Fonction à partir d'ici 
    /**
     * Retourne une liste du nombre de participant pour chaque activité fournis dans la $tabActivites.
     * 
     * @param $tabActivites (array<Activite>) Tableau contenant des activités
     * @return array<Integer>
     */
    protected function getNbParticipantsParActivite($tabActivites)
    {
        // Vérifie si l'utilisateur est authentifier 
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }
        
        /** Repository de Participer */
        $repParticiper = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');

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
     * @param $page (integer) indique la page actuel de recherche (fonctionn de pair avec $nbResultatPage)
     * @param $nbResultatPage (integer) indique le nombre de résultat a afficher par $page
     * @return array<Activite>
     */
    protected function getListeParticipationForUser($terminer, $page, $nbResultatPage)
    {
        // Vérifie si l'utilisateur est authentifié 
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }
        // On obtient l'utilisateur courrant
        $utilisateur = $this->getUser(); 

        // On récupère le manager & le repository des Participants : Participer
		$repParticiper = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
		// On récupère le manager & le repository des Activités : Activite
		$repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');

		// On récupère la liste des Participations liés à la personne connectée $utilisateur
		$listeEntiereParticiper = $repParticiper->findBy(array('idUtilisateur' => $utilisateur));
		
		// optimisable
		// on récupère la liste des ID des activité
		$listeIdActivite = array();
	    foreach ($listeEntiereParticiper as $participe) 
	    {
            $listeIdActivite[] = $participe->getIdActivite();
        }

        // on retourne la liste des activités, terminées ou non ($terminer), triées par ordre croissant selon la date,
        // avec une limite de $nbResultatPage activités
		return $repActivite->findBy(array('id'=>$listeIdActivite, 'estTerminee' => $terminer), 
		                            array('dateHeureRDV'=>'ASC'), 
		                            $nbResultatPage, 
		                            ($page-1)*$nbResultatPage);
    }
    
    
    
} // fin de "class ActivitesController extends Controller"