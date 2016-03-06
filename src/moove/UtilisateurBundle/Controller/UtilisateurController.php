<?php

namespace moove\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use moove\ActiviteBundle\Entity\Activite;
use moove\ActiviteBundle\Entity\Sport;
use moove\ActiviteBundle\Entity\Lieu;
use Symfony\Component\HttpFoundation\Request;
use moove\ActiviteBundle\Entity\Pratiquer;

class UtilisateurController extends Controller
{
    private function RecupSportNonPratique()
    {
        $user = $this->getUser();
        //On récupère le manager & le repository de pratiquer, d'activité et de participer
        $repPratiquer = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Pratiquer');
        $repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');
        $repParticipations = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
        //On récupère un tableau de pratiquer où il y a l'id de l'utilisateur
        $sportUser = $repPratiquer->findByUtilisateur($user);
        // on récupère le répository de Sport
        $repSport = $this->getRepository('Sport', 'Activite');
        $sports = $repSport->findAll();
        $sportNonPratique[0] = null;
        $nbSports = count($sports);
        $nbSportUser = 0;
        foreach($sports as $toutSports)
        {
            $compteur = 0;
            foreach($sportUser as $sportUtilisateur)
            {
                if($sportUtilisateur->getSport()->getNom() == $toutSports->getNom())
                {
                    $compteur = $compteur+1;
                    $nbSportUser = $nbSportUser+1;
                }
            }
            if($compteur == 0)
            {
                $sportNonPratique[0] = 1;
                $sportNonPratique[] = $toutSports;
            }
        }
        
        $tabInfoSportNonPratique[0] = $sportNonPratique;
        $tabInfoSportNonPratique[1] = $nbSportUser;
        
        return $tabInfoSportNonPratique;
    }
    // /!\ Actions métier
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/utilisateur/{idUtilisateur}"
     * 
     * @param idUtilisateur (integer) id de l'utilisateur 
     */
    public function profileAction($idUtilisateur)
    {   
        $this->checkAuthorization();
        
        if($idUtilisateur == $this->getUser()->getId())
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        
        // On récupère le manager & le repository de pratiquer, d'activité et de participer
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        $repActivite = $this->getRepository('Activite', 'Activite');
        $repParticipations = $this->getRepository('Participer', 'Activite');
        $repUtilisateur = $this->getRepository('Utilisateur');
        
        // On récupère l'utilisateur
        $user = $repUtilisateur->find($idUtilisateur);
            
        // On récupère un tableau de pratiquer où il y a l'id de l'utilisateur
        $tabSportNiveau = $repPratiquer->findByUtilisateur($user);
        // $nbSportNiveau = count($repPratiquer->findByUtilisateur($user));
        $nbSportNiveau = count($tabSportNiveau);
        
        $tabOrgaFull = $repActivite->findByOrganisateur($user);
        $nbOrganisations = array_reduce($tabOrgaFull, function ($carry, $item)
                                                        {
                                                            $carry += $item->getEstTerminee()? 0 : 1;
                                                            return $carry;
                                                        }, 0
                                        );
         $nbOrganisationsFinies = array_reduce($tabOrgaFull, function ($carry, $item)
                                                        {
                                                            $carry += $item->getEstTerminee()? 1 : 0;
                                                            return $carry;
                                                        }, 0
                                        );      
                                        
        // On récupère le nombre d'organisation non terminée
        //$nbOrganisations = count($repActivite->findBy(array('organisateur' => $user, 'estTerminee' => 0)));
        // On récupère la liste des participations à venir
        $listeParticipationEnApproche = $repParticipations->findByUtilisateurEstAccepter($user, false, 1);
        // oOn récupère le nombre de participations à venir
        $nbParticipations = count($listeParticipationEnApproche) - $nbOrganisations;
        
        // On récupère le nombre d'organisation terminées
        //$nbOrganisationsFinies = count($repActivite->findBy(array('organisateur' => $user, 'estTerminee' => 1)));
        // On récupère la liste des participations finis
        $listeParticipationsFinies = $repParticipations->findByUtilisateurEstAccepter($user, true, 1);
        // On récupère le nombre de participations finis
        $nbParticipationsFinies = count($listeParticipationsFinies) - $nbOrganisationsFinies;

        return $this->render('mooveUtilisateurBundle:Profile:show.html.twig', array(
            'user' => $user,
            'tabSportNiveau' => $tabSportNiveau,
            'nbSportNiveau' => $nbSportNiveau,
            'nbOrganisations' => $nbOrganisations,
            'nbParticipations' => $nbParticipations,
            'nbOrganisationsFinies' => $nbOrganisationsFinies,
            'nbParticipationsFinies' => $nbParticipationsFinies
        ));

  
        
        
        
        
    }
    public function editerSportsAction()
    {
        $utilisateur = $this->getUser();
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
         // On récupère un tableau de pratiquer où il y a l'id de l'utilisateur
        $tabSportNiveau = $repPratiquer->findByUtilisateur($utilisateur);
        // $nbSportNiveau = count($repPratiquer->findByUtilisateur($user));
        $nbSportNiveau = count($tabSportNiveau);
        return $this->render('mooveUtilisateurBundle:EditerSports:editerSports.html.twig', array(
            'tabSportNiveau' => $tabSportNiveau,
            'nbSportNiveau' => $nbSportNiveau
        ));
    }
    
    
    
    public function supprimerSportAction($idSport)
    {
        $user = $this->getUser();
        //on récupère le répository de pratiquer
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        $suppressionSport = $repPratiquer->supprimerSport($user, $idSport);
        return $this->redirect($this->generateUrl('moove_utilisateur_editer_sports'));
        
    }
    
    public function ajouterSportAction($idSport, $idNiveau)
    {
        // on récupère l'utilisateur qui accède à la page
        $user = $this->getUser();
        // on récupère le répository de Pratiquer
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        // on récupère le répository de Sport
        $repSport = $this->getRepository('Sport', 'Activite');
        // on récupère le sport sélectionné par l'utilisateur
        $sport = $repSport->find($id);
        // on récupère le niveau entré par l'utilisateur
        $niveau = $repNiveau->find($id);
        // on créé un nouvel objet Pratiquer que l'on hydrate
        $nouveauPratiquer = new Pratiquer();
        $nouveauPratiquer->setUtilisateur($user)
                         ->setSport($sport)
                         ->setNiveau($niveau);
        $em = $this->getDoctrine()->getManager();
        $em->persist($nouveauPratiquer);
        $em->flush();
        
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }
    
    public function choisirSportAction()
    {
        $user = $this->getUser();
        //On récupère le manager & le repository de pratiquer, d'activité et de participer
        $repPratiquer = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Pratiquer');
        $repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');
        $repParticipations = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
        //On récupère un tableau de pratiquer où il y a l'id de l'utilisateur
        $sportUser = $repPratiquer->findByUtilisateur($user);
        // on récupère le répository de Sport
        $repSport = $this->getRepository('Sport', 'Activite');
        $sports = $repSport->findAll();
        $nbSports = count($sports);
        $tabInfoSportNonPratique = $this->RecupSportNonPratique();
        $sportNonPratique = $tabInfoSportNonPratique[0];
        $nbSportUser = $tabInfoSportNonPratique[1];
        return $this->render('mooveUtilisateurBundle:AjouterSport:choisirSport.html.twig', array(
            'sportNonPratique' => $sportNonPratique,
            'nbSportUser' => $nbSportUser,
            'nbSports' => $nbSports));
    }
    
    public function choisirNiveauAction($idSport)
    {
        $tabInfoSportNonPratique = $this->RecupSportNonPratique();
        var_dump($sportNonPratique = $tabInfoSportNonPratique[0]);
        foreach($sportNonPratique as $sportNonPratiqueUser)
        {
            if($idSport == $sportNonPratiqueUser->getId())
            {
                $sport = $sportNonPratiqueUser;
            }
        }
        $niveaux = $this->getRepository('Niveau', 'Activite')->findAll();
        return $this->render('mooveUtilisateurBundle:AjouterSport:choisirNiveau.html.twig', 
            ['sport' => $sport,
             'niveaux' => $niveaux]);
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