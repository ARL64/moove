<?php

namespace moove\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use moove\ActiviteBundle\Entity\Pratiquer;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends Controller
{
    // /!\ Actions métier
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/utilisateur/{idUtilisateur}"
     * @param idUtilisateur (integer) id de l'utilisateur 
     * @return <i>Render</i> redirige sur mooveUtilisateurBundle:Profile:show.html.twig
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
    
    /**
     * Renvois sur la page d'édition des sports
     * @return <i>Render</i> redirige sur mooveUtilisateurBundle:EditerSports:editerSports.html.twig
     */
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
    
    /**
     * supprime le sport passer en paramêtre pour l'utilisateur authentifié
     * @return <i>Render</i> redirige sur moove_utilisateur_editer_sports
     */
    public function supprimerSportAction($idSport)
    {
        $user = $this->getUser();
        //on récupère le répository de pratiquer
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        $pratiquer = $repPratiquer->findOneBy(['utilisateur' => $user, 'sport' => $idSport]);
        
        if($pratiquer) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pratiquer);
            $em->flush();          
        }
        //$suppressionSport = $repPratiquer->supprimerSport($user, $idSport);
        return $this->redirect($this->generateUrl('moove_utilisateur_editer_sports'));
        
    }
    
    /**
     * Ajoute le sport, couplé avec le niveau, le tous passés en paramètre dans la fonction 
     * @return <i>Render</i> redirige sur fos_user_profile_show
     */
    public function ajouterSportAction(Request $request, $idSport)
    {
        // on récupère l'utilisateur qui accède à la page
        $user = $this->getUser();
        
        // On récupère le libellé du niveau choisi par l'utilsateur ('Intermédiaire, Expert, Confirmé, Débutant')
        $niveauChoisi = $request->get('niveau');
         // on récupère le répository de Niveau
        $repNiveau = $this->getRepository('Niveau', 'Activite');  
        // on récupère le niveau
        $niveau = $repNiveau->findOneBy(['libelle' => $niveauChoisi]);
        // on récupère le répository de Pratiquer
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        // on récupère le répository de Sport
        $repSport = $this->getRepository('Sport', 'Activite');
        // on récupère le sport sélectionné par l'utilisateur
        $sport = $repSport->find($idSport);

        // on créé un nouvel objet Pratiquer que l'on hydrate
        $nouveauPratiquer = new Pratiquer();
        $nouveauPratiquer->setUtilisateur($user)
                         ->setSport($sport)
                         ->setNiveau($niveau);
        $em = $this->getDoctrine()->getManager();
        $em->persist($nouveauPratiquer);
        $em->flush();
        $this->addFlash('success', "Le sport a bien été ajouté à votre compte.");
        
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }
    
    /**
     * renvois sur la page de choix des sports
     * @return <i>Render</i> redirige sur mooveUtilisateurBundle:AjouterSport:choisirSport.html.twig
     */
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
        $tabInfoSportNonPratique = $this->recupSportNonPratique();
        $sportNonPratique = $tabInfoSportNonPratique[0];
        $nbSportUser = $tabInfoSportNonPratique[1];
        return $this->render('mooveUtilisateurBundle:AjouterSport:choisirSport.html.twig', array(
            'sportNonPratique' => $sportNonPratique,
            'nbSportUser' => $nbSportUser,
            'nbSports' => $nbSports));
    }
    
    /**
     * 
     * @param $idSport <i>Sport</i> id du sport 
     * @return <i>Render</i> redirige sur mooveUtilisateurBundle:AjouterSport:choisirNiveau.html.twig
     */
    public function choisirNiveauAction($idSport)
    {
        //On fait appel à la fonction qui va récupérer tout les sports que ne pratique pas l'utilisateur
        $tabInfoSportNonPratique = $this->recupSportNonPratique();
        //Cette fonction retourne un tableau de 3 cases contenant dans la 3ème case un tableau d'id des sports non pratiqué par l'utilisateur.
        $sportNonPratique = $tabInfoSportNonPratique[2];
        //On récupère le nombre d'éléments présent dans $sportNonPratique
        $taille = count($sportNonPratique);
        //On récupère le manager & le repository de sport
        $repSport = $this->getRepository('Sport','Activite');
        
        foreach($sportNonPratique as $sportNonPratiqueUser)
        {
            if($idSport == $sportNonPratiqueUser)
            {
                $sport = $repSport->find($sportNonPratiqueUser);
            }
        }
        if(!isset($sport))
        {
            return $this->redirect($this->generateUrl('moove_utilisateur_choisir_sport'));
        }
        $niveaux = $this->getRepository('Niveau', 'Activite')->findAll();
        return $this->render('mooveUtilisateurBundle:AjouterSport:choisirNiveau.html.twig', 
            ['sport' => $sport,
             'niveaux' => $niveaux]);
    }
    
    /*
     * Confirme la modification du niveau d'un sport $idSport
     * @param $idSport <i>Sport</i> id du sport
     * @return <i>Redirect</i> redirige sur moove_utilisateur_editer_sports
     */
    public function confimerModifierNiveauSportAction(Request $request, $idSport)
    {
        // on récupère l'utilisateur qui accède à la page
        $user = $this->getUser();
        
        // On récupère le libellé du niveau choisi par l'utilsateur ('Intermédiaire, Expert, Confirmé, Débutant')
        $niveauChoisi = $request->request->get('niveau');
         // on récupère le répository de Niveau
        $repNiveau = $this->getRepository('Niveau', 'Activite');  
        // on récupère le niveau
        $nouveauNiveau = $repNiveau->findOneBy(['libelle' => $niveauChoisi]);
        // on récupère le répository de Pratiquer
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        // on récupère le répository de Sport
        $repSport = $this->getRepository('Sport', 'Activite');
        // on récupère le sport sélectionné par l'utilisateur
        $sport = $repSport->find($idSport);

        // on récupère l'ancien Pratiquer
        $pratiquerAModifier = $repPratiquer->findOneBy(['utilisateur' => $user, 'sport' => $sport]);
        // on modifie le niveau du sport
        $pratiquerAModifier->setNiveau($nouveauNiveau);
        $em = $this->getDoctrine()->getManager();
        $em->persist($pratiquerAModifier);
        $em->flush();
        $this->addFlash('success', "Votre niveau a bien été modifié !");
        
        return $this->redirect($this->generateUrl('fos_user_profile_show'));   
    }
    
        
    /**
     * supprime l'utilisateur passer en paramètre
     * @param $idUtilisateur <i>Utilisateur</i> id de l'utilisateur 
     * @return <i>Render</i> redirige sur moove_activite_tableauDeBord si la procédure est arreté, OU fos_user_security_logout si ça à fonctionné
     */
    public function supprimerUtilisateurAction($idUtilisateur)
    {
        //Récupération de l'utilisateur et des divers répository
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $repPratiquer = $this->getRepository('Pratiquer','Activite');
        $repParticiper = $this->getRepository('Participer','Activite');
        $repActivite = $this->getRepository('Activite','Activite');
        $repUtilisateur = $this->getRepository('Utilisateur');
        
        //On vérifie que l'utilisateur qui demande à supprimer son compte est bien l'utilisateur connecté
        if($user->getId() == $idUtilisateur)
        {
            //On récupère un tableau de Participation où l'id de l'utilisateur est présent
            $tabParticipation = $repParticiper->findBy(array('utilisateur' => $user->getId()));
            //On récupère un tableau de Pratiquer où l'id de l'utilisateur est présent
            $tapPratiquer = $repPratiquer->findBy(array('utilisateur' => $user->getId()));
            //On parcourt de le tableau de particper
            foreach($tabParticipation as $participation)
            {
                //On regarde si l'utilisateur est l'organisateur de l'activité
                if($participation->getActivite()->getOrganisateur()->getId() == $user->getId())
                {
                    //on supprimer l'activité
                    $supprimerActivite = $repActivite->supprimerActivite($participation->getActivite()->getId(), $user->getId());
                }
                else
                {
                    //sinon on regarde si la demande de l'utilisateur est en attente ou refusée 
                    // auquel cas on la passe a accepté pour qu'il puisse quitter l'activité
                    if ($participation->getEstAccepte() == 0 || $participation->getEstAccepte() == 2 )
                    {
                        $participation->setEstAccepte(1);
                        $quitterActivite = $repParticiper->quitterActivite($participation->getActivite()->getId(), $user->getId());
                    }
                    else
                    {
                        //sinon on quitte simplement l'activité
                        $quitterActivite = $repParticiper->quitterActivite($participation->getActivite()->getId(), $user->getId());
                    }
                    
                }
            }
            //On parcour le tableau des sports afin de les supprimer
            foreach ($tapPratiquer as $pratiquer) 
            {
                $supprimerSport  = $repPratiquer->supprimerSport($user->getId(),$pratiquer->getSport()->getId());
            }
            //on supprimer l'utilisateur
            $em->remove($user);
            $em->flush();
            $this->addFlash('notice', "Votre compte a bien été supprimé");
            return $this->redirect($this->generateUrl('fos_user_security_logout'));
        }
        else
        {
            $this->addFlash('notice', "Vous ne pouvez pas faire ça !");
            return $this->redirect($this->generateUrl('moove_activite_tableauDeBord'));
        }
    }
    
    public function modifierNiveauSportAction($idSport)
    {
        $repSport = $this->getRepository('Sport','Activite');
        $repPratiquer = $this->getRepository('Pratiquer', 'Activite');
        $user = $this->getUser();
        $pratiquer = $repPratiquer->findOneBy([
            'sport' => $idSport,
            'utilisateur' => $user
        ]);
        
        // Si on ne trouve pas le sport, il n'est pas pratiqué par l'utilisateur, on le redigire donc vers ses sports
        if(!$pratiquer) {
            return $this->redirect($this->generateUrl('moove_utilisateur_editer_sports'));
        }
        
        // on récupère l'ancien niveau
        $ancienNiveau = $pratiquer->getNiveau()->getLibelle();

        // Si l'ancien niveau est débutant, on initialise le slider de la vue à Débutant
        if($ancienNiveau === "Débutant") $fromNiveau = 0;
        // Si l'ancien niveau est intermédiaire, on initialise le slider de la vue à Intermédiaire
        else if($ancienNiveau === "Intermédiaire") $fromNiveau = 1;
        // Si l'ancien niveau est confirmé, on initialise le slider de la vue à Confirmé
        else if($ancienNiveau === "Confirmé") $fromNiveau = 2;
        // Si l'ancien niveau est expert, on initialise le slider de la vue à Expert
        else if($ancienNiveau === "Expert") $fromNiveau = 3;
        // on récupère le sport
        $sport = $repSport->find($idSport);
        // on récupère tous les niveaux
        $niveaux = $this->getRepository('Niveau', 'Activite')->findAll();
        
            //
        return $this->render('mooveUtilisateurBundle:EditerSports:modifierNiveau.html.twig', 
            ['sport' => $sport,
             'niveaux' => $niveaux,
             'fromNiveau' => $fromNiveau]);
    }
    
    // /!\ Fonction à partir d'ici 
    
    /**
     */
     
             
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
     * @param $nomRepository <i>string<i> nom du repository souhaité
     * @param $nomBundle <i>string</i>='Utilisateur' nom du bundle (si différent de celui actuel)
     * @return </i>repository</i>
     */
    protected function getRepository($nomRepository, $nomBundle = 'Utilisateur')
    {
        return $this->getDoctrine()->getManager()->getRepository('moove'.$nomBundle.'Bundle:'.$nomRepository);
    }
  
    /**
     * Récupère un tableau de 3 case.
     * La premier case est un tableau regroupant les sports non pratiqué
     * La deuxième case est un entier contenant le nombre de sport non pratiqué
     * La troisième case est un array contenant les id de tous les sport non pratiqué
     * @return Array<Array<Sport>, int, Array<int>> retourn un tableau contenant la liste des sports, le nombre total de sport correspondant, et l'id des sport ?
     */  
    private function recupSportNonPratique()
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
        $idSportNonPratique[0] = null;
        
        //On parcourt tous les sports
        foreach($sports as $toutSports)
        {
            //On initialise une variable qui prendra la valeur 1 si le sport est déjà pratiqué par l'utilisateur, 0 sinon
            $compteur = 0;
            
            //On parcours tous les sports de l'utilisateur
            foreach($sportUser as $sportUtilisateur)
            {
                //Si les 2 sports courant sont identiques on passe le compteur à 1 et 
                //on incrémente nbSportUser qui permettra de savoir par la suite si l'utilisateur n'a aucun sport à ajouter pour adapter le message affiché
                if($sportUtilisateur->getSport()->getNom() == $toutSports->getNom())
                {
                    $compteur = $compteur+1;
                    $nbSportUser = $nbSportUser+1;
                }
            }
            //Si les 2 sports courant ne sont pas identiques, cela signifie que l'utilisateur ne le pratique pas encore
            if($compteur == 0)
            {
                //On place 1 dans la première case du tableau qui était initialisé à null (on pourra faire une vérifiction dans la vue pour adapter l'action d'affichage)
                $sportNonPratique[0] = 1;
                //On récupère l'objet sport contenu dans $toutSport
                $sportNonPratique[] = $toutSports;
                //On récupère l'id du sport pour l'action choisirNiveauAction
                $idSportNonPratique[] = $toutSports->getId();
            }
        }
        $tabInfoSportNonPratique[0] = $sportNonPratique;
        $tabInfoSportNonPratique[1] = $nbSportUser;
        $tabInfoSportNonPratique[2] = $idSportNonPratique;
        
        return $tabInfoSportNonPratique;
    }
} // fin de "class ActivitesController extends Controller"