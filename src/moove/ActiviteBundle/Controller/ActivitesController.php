<?php

namespace moove\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ActivitesController extends Controller
{
    
    public function tableauDeBordAction()
    {
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordAccueil.html.twig');
    }
    
    public function detailsActiviteAction($idActivite)
    {
        $repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');
        $activite = $repActivite->find($idActivite);
        return $this->render('mooveActiviteBundle:Activite:detailsActivite.html.twig', array('activite' => $activite));
    }
    
    public function historiqueAction($page)
    {   // Liste des activitées finies
        $tabActivites = $this->getListeParticipationForUser(true, $page, 20);
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig',
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants));
    }
    
    public function enCoursAction($page)
    {   // Liste des activitées en approche
        $tabActivites = $this->getListeParticipationForUser(false, $page, 20);
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants));
    }
    
    
    protected function getNbParticipantsParActivite($tabActivites)
    {
        // Vérifie si l'utilisateur est authentifier 
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }
        
        // On prend le manager & le dossier des Participants
        $repParticiper = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');

		// on récupère la liste des participants, et on la convertie en quantité
        $listeNbParticipant = array();
	    foreach ($tabActivites as $activite) 
	    {
	        $listeParticipant = $repParticiper->findByIdActivite($activite->getId());
            $listeNbParticipant[] = count($listeParticipant);
        }
        return $listeNbParticipant;
    }
    
    protected function getListeParticipationForUser($terminer, $page, $nbResultatPage)
    {
        // Vérifie si l'utilisateur est authentifier 
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }
        // On obtien l'utilisateur courrant
        $utilisateur = $this->getUser(); 


        // On prend le manager & le dossier des Participants
		$repParticiper = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
		$repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');

		// On récupère la liste des Participation liés à cette personne
		$listeEntiereParticiper = $repParticiper->findBy(array('idUtilisateur' => $utilisateur));
		
		// optimisable
		// on récupère la liste des ID des activité
		$listeIdActivite = array();
	    foreach ($listeEntiereParticiper as $participe) 
	    {
            $listeIdActivite[] = $participe->getIdActivite();
        }

		return $repActivite->findBy(array('id'=>$listeIdActivite, 'estTerminee' => $terminer), 
		                            array('dateHeureRDV'=>'ASC'), 
		                            $nbResultatPage, 
		                            ($page-1)*$nbResultatPage);
    }
}
