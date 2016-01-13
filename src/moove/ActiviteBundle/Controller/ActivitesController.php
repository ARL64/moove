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
        return $this->render('mooveActiviteBundle:Accueil:detailsActivite.html.twig', array('activite' => $activite));
    }
    
    public function historiqueAction($page)
    {   // Liste des activitées finies
        $tabActivites = $this->getListeActiviteForUser(true, $page);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig', array('tabActivites' => $tabActivites));
    }
    
    public function enCoursAction($page)
    {   // Liste des activitées en approche
        $tabActivites = $this->getListeActiviteForUser(false, $page);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', array('tabActivites' => $tabActivites));
    }
    
    
    
    protected function getListeActiviteForUser($terminer, $page)
    {
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }
        $utilisateur = $this->getUser();


        // On prend le manager & le dossier des Participants
		$repParticiper = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
		//$repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');

		// On récupère la liste des activités liés à cette personne
		$nbResultatPage = 20;
		$listeEntiereActivite = $repParticiper->findBy(array('idUtilisateur'=>$utilisateur));
		
		/*$listeEntiereActivite = $repParticiper->findBy(
                                    array('idUtilisateur' => $utilisateur), // Critere
                                    null,        // Tri
                                    $nbResultatPage,                              // Limite
                                    ($page-1)*$nbResultatPage                     // Offset
        );*/
		//$tabActivites = $listeEntiereActivite->findBy(array('estTermine' => $terminer));
		
		return $listeEntiereActivite;
    }
}
