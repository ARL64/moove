<?php

namespace moove\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use moove\ActiviteBundle\Entity\Activite;
use moove\ActiviteBundle\Entity\Sport;
use moove\ActiviteBundle\Entity\Lieu;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends Controller
{
    // /!\ Actions métier
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/utilisateur/{idUtilisateur}"
     * 
     * @param idUtilisateur (integer) id de l'utilisateur 
     */
    public function profileAction($idUtilisateur)
    {   
        $this->checkAuthorization();
        
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        $repUtilisateur = $this->getRepository('Utilisateur');
        
        $user = $repUtilisateur->find($idUtilisateur);
        $tabSportNiveau = $repPratiquer->findByUtilisateur($user);
        $nbSportNiveau = count($tabSportNiveau);

        return $this->render('mooveUtilisateurBundle:Profile:show.html.twig', array(
            'user' => $user,
            'tabSportNiveau' => $tabSportNiveau,
            'nbSportNiveau' => $nbSportNiveau
        ));
    }
    
    
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
     * @param $nomBundle (string = 'Activite') nom du bundle (si différent de celui actuel)
     * @return (repository)
     */
    protected function getRepository($nomRepository, $nomBundle = 'Utilisateur')
    {
        return $this->getDoctrine()->getManager()->getRepository('moove'.$nomBundle.'Bundle:'.$nomRepository);
    }
    
} // fin de "class ActivitesController extends Controller"