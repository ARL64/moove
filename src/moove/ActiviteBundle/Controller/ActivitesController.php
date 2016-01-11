<?php

namespace moove\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ActivitesController extends Controller
{
    public function historiqueAction($name, $page)
    {   // Liste des activitées finies
        //$listeDesActivites = getListeActiviteForUser($nomUtilisateur, true);
        return $this->render('mooveActiviteBundle:Accueil:listActiviteUtilisateur.html.twig', array('name' => $name));
    }
    
    public function enCoursAction($name, $page)
    {   // Liste des activitées en approche
    
        return $this->render('mooveActiviteBundle:Accueil:listActiviteUtilisateur.html.twig', array('name' => $name));
    }
    
    
    
    protected function getListeActiviteForUser($nomUtilisateur, $terminer)
    {
        // On prend le manager & le dossier des Utilisateur
        $manager = $this->getDoctrine()->getManager();
		$repUtilisateur = $manager->getRepository('MooveActiviteBundle:Utilisateur');

        // On recherche la seul occurence portant le pseudo donner
		$utilisateur = $repUtilisateur->findOneByNom($nomUtilisateur);
		
		// On récupère la liste des activités liés à cette personne
		$listeEntiereActivite = $utilisateur->getListeActivites();
		
		// pour chaque activité dans la liste
		foreach ($listeEntiereActivite as $i) {
		    // on vérifie si l'activité est terminée ou non et on génère la liste en fonction
		    // si $terminer = true, alors seules les activités terminées apparaitrons, et inversement. (équivalent a "$i.getEstTerminee == $terminer")
		    if($i.getEstTerminee() && $terminer)
		        $listeActivite[] = $i;
		}

		return $listeActivite;
    }
}
