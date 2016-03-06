<?php

namespace moove\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use moove\ActiviteBundle\Entity\Activite;
use moove\ActiviteBundle\Entity\Sport;
use moove\ActiviteBundle\Entity\Lieu;
use moove\ActiviteBundle\Entity\Participer;
use Symfony\Component\HttpFoundation\Request;
use \GeocodeMapsGeocoder;
use moove\ActiviteBundle\Form\ActiviteType;
require_once __DIR__ . '/../../../../vendor/jstayton/google-maps-geocoder/src/GoogleMapsGeocoder.php';

class ActivitesController extends Controller
{
    // /!\ Actions métier
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/accueil"
     * 
     */
    public function tableauDeBordAction()
    {
        //on récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        /** Repository de Participer */
        $repParticipations = $this->getRepository('Participer');
        /** Repository de Activite */
        $repActivite = $this->getRepository('Activite');
        // On récupère les activités où l'utilisateur connecté est organisateur qui se ne pas terminées
        $listeOrganisationEnApproche = $repParticipations->findByUtilisateurEstAccepter($utilisateur, false, 1);
        // On récupère le nombre d'organisations des activités qui ne sont pas terminées
        $nbOrganisations = count($repActivite->findBy(array('organisateur' => $utilisateur, 'estTerminee' => 0)));
        
        // On récupère les demandes de participations envoyées aux autres utilisateurs
        $listeDesDemandesDeParticipationsEnAttente = $repParticipations->findByOrganisateur($utilisateur, 0, false);
        // On récupère le nombre de demandes de participations envoyées aux autres utilisateurs
        $nbDemandesParticipationsActiviteEnAttente = count($listeDesDemandesDeParticipationsEnAttente);
        // On récupère les participations des utilisateurs qui veulent participer aux activités de l'utilisateur $utilisateur
        $listeParticipationEnApproche = $repParticipations->findByUtilisateurEstAccepter($utilisateur, false, 1);
        // On récupère le nombre de participations
        $nbParticipations = count($listeParticipationEnApproche) - $nbOrganisations;
        // On récupère les demandes de participations des autres utilisateurs 
        $listeDesDemandesEnAttente = $repActivite->findByUtilisateurAccepter($utilisateur, 0);
        // On récupère le nombres de demandes de participations de l'utilisateur $utilisateur où il n'a pas encore été accepté
        $nbDemandesEnAttente = count($listeDesDemandesEnAttente);
       
       // BUG //
       /*
        La liste des activité a un problème, ce n'est pas les bonnes valeurs...
       */

        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordAccueil.html.twig', 
                            array(  
                                    'nbParticipations' => $nbParticipations, 
                                    'listeParticipationEnApproche' => $listeParticipationEnApproche,
                                    
                                    'nbDemandesEnAttente' => $nbDemandesEnAttente, 
                                    'listeDemandesEnAttente' => $listeDesDemandesEnAttente,

                                    'nbDemandesEnAttenteOrganisateur' => $nbDemandesParticipationsActiviteEnAttente,
                                    'ListeDemandeAValide' => $listeDesDemandesDeParticipationsEnAttente,
                                    
                                     'nbOrganisations' => $nbOrganisations,
                                    'listeOrganisationEnApproche' => $listeOrganisationEnApproche
                                    ));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activite/{idActivite}"
     * 
     */
    public function detailsActiviteAction($idActivite)
    {
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
           $niveauOrganisateur = $resultatNiveauOrganisateur->getLibelle();
        }
         
        // On récupère un tableau d'objet Participer $tabParticiper ordonner par ID. Plus l'ID est élever, plus l'acceptation est récente.
        $tabParticiper = $repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 1), array('id' => 'DESC'));
        // On récupère le nombre de participants de l'activité
        $nbParticipants = count($tabParticiper);
        
        if($estOrganisateur)
                $tabParticiper = array_merge($repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 0)), $tabParticiper);

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
    public function historiqueAction(Request $request)
    {   
        // On récupère l'utilisateur
        $utilisateur = $this->getUser(); 
        
        $request = Request::createFromGlobals();

        $order = $this->getOrderBy($request->query->get('order'));
        $type = $request->query->get('type');
        if(is_null($type))
            $type = "DESC";
        $nbResultatParPage = 4;
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        // On récupère les activités où l'utilisateur connecté est accepté à l'activité
        $findTabActivites = $repActivite->findByUtilisateurAccepter($utilisateur->getId(), 1, true, $order, $type);
        // On récupère les activités pagninées avec 2 éléments par page
        $tabActivites  = $this->get('knp_paginator')->paginate($findTabActivites, $request->query->getInt('page', 1), $nbResultatParPage);
        /** Liste du nombre de participations */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig', array(
                                    'tabActivites' => $tabActivites, 
                                    'tabNbParticipants' => $tabNbParticipants,
                                    'nbResultatParPage' => $nbResultatParPage));
    }
    
    public function getOrderBy($order)
    {
        switch ($order) 
        {
            case 'sport':
                $temps = "s.nom";
                break;
            case 'organisateur':
                $temps = "u.prenom";
                break;
            case 'demande':
                $temps = "p.estAccepte";
                break;
            case 'depart':
                $temps = "a.lieuDepart";
                break;
            case 'arrive':
                $temps = "a.lieuArrivee";
                break;
            case 'rdv':
                $temps = "a.lieuRDV";
                break;
            default:
                $temps = "a.dateHeureRDV";
                break;
        }
        
        return $temps;
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activites"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function enCoursAction(Request $request)
    {

        /** liste des activités de l'utilisateur courrant*/
        $utilisateur = $this->getUser();
        
        $request = Request::createFromGlobals();
        
        $order = $this->getOrderBy($request->query->get('order'));
        $type = $request->query->get('type');
        if(is_null($type))
            $type = "DESC";
        $nbResultatParPage = 4;
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        $repParticiper = $this->getRepository('Participer'); // temps
        // On récupère les activités de l'utuilisateur $utilisateur lorsque l'activité est terminée
        $findTabActivites = $repActivite->findByUtilisateur($utilisateur->getId(), false, $order, $type);        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
        $tabNbParticipants = $this->getNbParticipantsParActivite($findTabActivites);

        $tabEstAccepte = [];
        foreach($findTabActivites as $activite)
        {
            $tabEstAccepte[] = $this->estAccepte($activite);
        }
            
        $tabActivites  = $this->get('knp_paginator')->paginate($findTabActivites, $request->query->getInt('page', 1), $nbResultatParPage);
        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array(  'tabActivites' => $tabActivites, 
                                    'tabNbParticipants' => $tabNbParticipants, 
                                    'tabEstAccepte' => $tabEstAccepte,
                                    'nbResultatParPage' => $nbResultatParPage));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/rechercher"
     * 
     */
    public function rechercherActiviteAction()
    {
        
        $request = Request::createFromGlobals();
        
        $repSport = $this->getRepository('Sport');
        $tabSport = $repSport->findBy(array(), array('nom'=>'asc'));

        $repNiveau = $this->getRepository('Niveau');
        $tabNiveau = $repNiveau->findBy(array(), array('libelle'=>'asc'));
        
        if(!empty($_POST))
        {
            
            //var_dump($_POST['date']);
            
            $tabHeure = explode(";", $_POST['heure']);
            $nbPlaceTab = explode(";", $_POST['nbPlaces']);
            if($_POST['date'] != "")
            {
                $tabDate = explode("/", $_POST['date']);
                $datePrecise = $tabDate[2] . "-" . $tabDate[1] . "-" . $tabDate[0];
            }
            else
            {
                $datePrecise = null;
            }

            $heureMin = $tabHeure[0]. "h00";
            $heureMax = $tabHeure[1]. "h00";
            
            //var_dump($heureMin);

            $sportSelected = false;
            $arraySport = "[";
            foreach($tabSport as $sport)
            {
               if(array_key_exists('name_'.$sport->getNom(), $_POST)) // cette condition bug
               {
                   $arraySport .= $sport->getNom() . ",";
                   $sportSelected = true;
               }
            }
            if($sportSelected)
                $arraySport = substr($arraySport, 0, strlen($arraySport)-1) . "]";
            else 
                $arraySport = null;
            $sport = $arraySport;
            
            $niveauSelected = false;
            $arrayNiveau = "[";
            foreach($tabNiveau as $niveau)
            {
               if(array_key_exists('name_'.$niveau->getLibelle(), $_POST)) // cette condition bug
               {
                   $arrayNiveau .= $niveau->getLibelle() . ",";
                   $niveauSelected = true;
               }
            }
            if($niveauSelected)
                $arrayNiveau = substr($arrayNiveau, 0, strlen($arrayNiveau)-1) . "]";
            else 
                $arrayNiveau = null;
            $niveau = $arrayNiveau;
            
            
            $photo = $_POST['photo'];
            $nbPlaceRestante = intval($_POST['nbPlacesRestantes']);
            $nbPlaceMin = intval($nbPlaceTab[0]);
            $nbPlaceMax = intval($nbPlaceTab[1]);
            
            $distanceMax = intval($_POST['rayonRecherche']);
            
        }
        else 
        {
            $datePrecise = $request->query->get('date');                    // format : 2016-04-14 (YYYY-mm-dd) (date)
            $heureMin = $request->query->get('hMin');                       // format : 07h30 (HHhmm) (date) (nécéssite datePrécise)
            $heureMax = $request->query->get('hMax');                       // format : 14h45 (HHhmm) (date) (nécéssite datePrécise)
            $sport = $request->query->get('sport');                         // format : [Cyclisme,Jogging,Ski,Randonee] (array)
            $niveau = $request->query->get('niveau');                       // format : [Tous,Debutant,Intermediaire,Confirme] (array)
            $photo = $request->query->get('photo');                         // format : yes (bool)
            $nbPlaceRestante = $request->query->get('nbPlace');                  // format : 10  (int)
            $nbPlaceMin = $request->query->get('placeRestanteMin'); // format : 2  (int)
            $nbPlaceMax = $request->query->get('placeRestanteMax'); // format : 7  (int)
            $distanceMax = $request->query->get('distance');                // format : 10  NON DISPONIBLE
        }

        $order = $this->getOrderBy($request->query->get('order'));
        $type = $request->query->get('type');
        if(is_null($type))
            $type = "ASC";
        $nbResultatParPage = 4;
        
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        // On récupère toutes les activités dans $tabActivites
        //$tabActivites = $repActivite->findAll();
        $tabActivites = $repActivite->findWhitCondition($datePrecise, $heureMin, $heureMax, $sport, $niveau, $photo, $nbPlaceRestante, $nbPlaceMin, $nbPlaceMax, $distanceMax, $order, $type);


        
        // On compte combien il y a d'activités
        $nbActivites = count($tabActivites);
        
        return $this->render('mooveActiviteBundle:Activite:rechercherActivites.html.twig', array(
            'tabActivites' => $tabActivites,
            'nbActivites' => $nbActivites,
            'tabSport' => $tabSport,
            'tabNiveau' => $tabNiveau
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
        $lieuRDV = new Lieu();
        $today = getDate();
        //$jour = $today['wday'];
        $annee = $today['year'];
        //$mois = mktime( 0, 0, 0, $today['mon'], 1, $today['year'] );
        // $nombreDeJoursMois = intval(date("t",$mois));
        // On initialise l'organisteur avec l'utilisateur qui est entrain de créer l'activité
        $activite   ->setOrganisateur($this->getUser())
                    ->setDateCreation(new \Datetime())
                    ->setDateFermeture(new \Datetime())
                    ->setDateHeureRDV(new \Datetime())
                    ->setEstTerminee(false)
                ;
        // On crée le formulaire permettant de saisir un livre
        $formulaireActivite = $this->createForm(new ActiviteType, $activite);
        
        /* On analyse la requête courante pour savoir si le formulaire a été soumis ou pas.
        Dans le cas d'une soumission, les données saisies par l'utilisateur viendront remplir
        l'objet $activite*/
        $formulaireActivite->handleRequest($requeteUtilisateur);
        
        if($formulaireActivite->isValid()) // Le formulaire a été soumis
        {
            // On récupère l'adresse les adresses des lieux
            $adresseLieuRDV = $formulaireActivite->getData()->getAdresseLieuRDV();
            $adresseLieuDepart = $formulaireActivite->getData()->getAdresseLieuDepart();
            $adresseLieuArrivee = $formulaireActivite->getData()->getAdresseLieuArrivee();
            
            // On récupère les infos de chaque lieu dans un nouvel objet
            $lieuRDV = $this->getInfosAdresse($adresseLieuRDV);
            
            // On appelle le gestionnaire d'entité
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            
            if(!(is_null($adresseLieuDepart) || (is_null($adresseLieuArrivee)))) {
                $lieuDepart = $this->getInfosAdresse($adresseLieuDepart);
                $lieuArrivee = $this->getInfosAdresse($adresseLieuArrivee);
                $gestionnaireEntite->persist($lieuDepart);
                $gestionnaireEntite->persist($lieuArrivee);
                $activite->setLieuDepart($lieuDepart)
                         ->setLieuArrivee($lieuArrivee);
            }
            
            // On persiste les lieux
            $gestionnaireEntite->persist($lieuRDV);

            
            // On ajoute le lieu à l'activité
            $activite->setLieuRDV($lieuRDV);
            
            // On créé un objet Participer
            $participer = new Participer();
            
            // On remplit l'objet Participer avec l'activité et l'utilisateur organisateur
            $participer->setActivite($activite)
                       ->setUtilisateur($this->getUser())
                       ->setEstAccepte(1);
            
            // On persiste la participation dans la base de données
            $gestionnaireEntite->persist($participer);
            
            //On enregistre l'objet $activite en base de données
            $gestionnaireEntite->persist($activite);
            $gestionnaireEntite->flush();
            // $this->get('session')
            $requeteUtilisateur->getSession()->getFlashBag()->add('notice', 'Activité publiée.');
            //On redirige vers la page de visualisation de l'activité ajouté
            return $this->redirect($this->generateUrl('moove_activite_detailsActivite',
                                                      array('idActivite' => $activite->getId())));
        }
        //A ce point, le visiteur arrive sur la page qui doit afficher le formulaire
        return $this->render('mooveActiviteBundle:Activite:proposerActivite.html.twig',
                             array('formulaireActivite' => $formulaireActivite->createView()));
    }
    
    public function accepterDemandeParticipationActiviteAction($idActivite, $idUtilisateur)
    {
        $estAccepte = $this->demandeParticipation($idActivite, $idUtilisateur, 1);
        if($estAccepte)
            // On ajoute un message flash à la session afin de notifier l'utilisateur
            $this->addFlash('notice', "L'utilisateur a bien été accepté !");
        else
            $this->addFlash('notice', "La data limite d'inscription est dépassée ou il n'y a plus de places disponibles dans l'activité.");
        
        return $this->redirect($this->generateUrl('moove_activite_detailsActivite', ['idActivite' => $idActivite]));
    }
    
    public function refuserDemandeParticipationActiviteAction($idActivite, $idUtilisateur)
    {
        $estRefuse = $this->demandeParticipation($idActivite, $idUtilisateur, 2);
        if($estRefuse)
            // On ajoute un message flash à la session afin de notifier l'utilisateur
            $this->addFlash('notice', "L'utilisateur a bien été refusé !");
        else
            $this->addFlash('notice', "La data limite d'inscription est dépassée ou il n'y a plus de places disponibles dans l'activité.");
        return $this->redirect($this->generateUrl('moove_activite_detailsActivite', ['idActivite' => $idActivite]));
    }
    
    public function demandeParticipationActiviteAction($idActivite, $idUtilisateur)
    {
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');

        // On récupère l'activité
        $activite = $repActivite->find($idActivite);
        
        // On récupère le nombre de participants de l'activité
        $nbParticipants = $this->getNbParticipantsActivite($idActivite);
        
        // On récupère la date du jour
        $dateAujourdhui = new \Datetime();
            
        // On vérifié que l'activité n'est pas remplie et que l'activité n'est pas terminée
        if($activite->getNbPlaces() > $nbParticipants && $dateAujourdhui < $activite->getDateFermeture() && !$activite->getEstTerminee())
        {
            // On récupère le repository Activite
            $repParticiper = $this->getRepository('Participer');
            
            // On récupère l'objet Participer de l'utilisateur ayant demandé la participation avec l'id $idUtilisateur
            $participer = new Participer();
            // On accepte l'utilisateur dans l'activité
            $participer->setActivite($activite)
                       ->setUtilisateur($this->getUser())
                       ->setEstAccepte(0);
            // On appelle le gestionnaire d'entité
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            
            // On persiste la participation dans la base de données
            $gestionnaireEntite->persist($participer);
            
            // On enregistre la modification en base de données
            $gestionnaireEntite->flush();
            
            // On ajoute un message flash à la session afin de notifier l'utilisateur que la demande a été envoyée
            $this->addFlash('notice', "Votre demande de participation a été envoyée ! L'organisateur doit accepter votre demande pour que vous participiez à l'activité.");
            return $this->redirect($this->generateUrl('moove_activite_detailsActivite', ['idActivite' => $activite->getId()]));
        }
        
        $this->addFlash('notice', "La data limite d'inscription est dépassée ou il n'y a plus de places disponibles dans l'activité.");
        return $this->redirect($this->generateUrl('moove_activite_detailsActivite', ['idActivite' => $activite->getId()]));
    }
        
    public function quitterActiviteAction($idActivite, $idUtilisateur) 
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // if($utilisateur->getId() == $idUtilisateur)
        
        // On récupère le repository participer
        $repParticiper = $this->getRepository('Participer');
        $quitterActivite = $repParticiper->quitterActivite($idActivite, $utilisateur);
        return $this->redirect($this->generateUrl('moove_activite_detailsActivite', array ('idActivite'=> $idActivite)));
        
    }
    
    public function supprimerActiviteAction($idActivite, $organisateur)
    {
        $utilisateur = $this->getUser()->getId();
        if($organisateur == $utilisateur)
        {
            //On récupère le répository d'Activité
            $repActivite = $this->getRepository('Activite');
            
            //on supprimer l'activité
            $supprimerActivite = $repActivite->supprimerActivite($idActivite, $organisateur);
            $this->addFlash('notice', "Votre activité a bien été supprimée");
            return $this->redirect($this->generateUrl('moove_activite_tableauDeBord'));
        }
        else
        {
           $this->addFlash('notice', "Vous ne pouvez pas faire ça !");
            return $this->redirect($this->generateUrl('moove_activite_tableauDeBord'));
        }
    }
    

    // /!\ Fin actions métier
    
    
    
    
    
    
    
    
    
    // /!\ Fonction à partir d'ici 
    
    /**
     * Vérifie que l'utilisateur soit connecter. Si ce n'est pas le cas, il est re-dirigé
     */
    private function checkAuthorization()
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
    private function getNbParticipantsParActivite($tabActivites)
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
    
    private function getNbParticipantsActivite($idActivite)
    {
        $repParticiper = $this->getRepository('Participer');
        // On récupère un tableau d'objet Participer $tabParticiper
        $tabParticiper = $repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 1));
        // On récupère le nombre de participants de l'activité
        $nbParticipants = count($tabParticiper);
        
        return $nbParticipants;
    }

    /**
     * Retourne un booléen permettant d'indiquer si l'utilisateur connecté participe à l'activité $idActivite
     * 
     * @param $idActivite
     * @return boolean
     */
    private function estParticipantDeActivite($activite)
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
    private function estAccepte($activite)
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
    
    private function estOrganisateur($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');

        return ($repActivite->find($activite)->getOrganisateur() == $utilisateur);
    }
    
    private function demandeParticipation($idActivite, $idUtilisateur, $accepte)
    {
         // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        // On récupère l'organisateur
        $organisateur = $this->getUser();
        // On récupère l'activité
        $activite = $repActivite->find($idActivite);
        
        // On vérifie l'organisateur est bien la personne connectée
        if($activite->getOrganisateur() == $organisateur)
        {
            // On récupère le nombre de participants de l'activité
            $nbParticipants = $this->getNbParticipantsActivite($idActivite);
            
            // On récupère la date du jour
            $dateAujourdhui = new \Datetime();
            
            // On vérifié que l'activité n'est pas remplie et que l'activité n'est pas terminée
            if($activite->getNbPlaces() > $nbParticipants && $dateAujourdhui < $activite->getDateFermeture() && !$activite->getEstTerminee())
            {
                // On récupère le repository Activite
                $repParticiper = $this->getRepository('Participer');
                
                // On récupère l'objet Participer de l'utilisateur ayant demandé la participation avec l'id $idUtilisateur
                $participer = $repParticiper->findOneBy(['utilisateur' => $idUtilisateur, 'activite' => $idActivite]);
                // On accepte l'utilisateur dans l'activité
                $participer->setEstAccepte($accepte);
                // On appelle le gestionnaire d'entité
                $gestionnaireEntite = $this->getDoctrine()->getManager();
                
                // On persiste la participation dans la base de données
                $gestionnaireEntite->persist($participer);
                
                // On enregistre la modification en base de données
                $gestionnaireEntite->flush();
                
                return true;
            }
            return false;
        }
        $this->addFlash('notice', "Vous n'êtes pas authorisé à faire cette action.");
        return false;
    }
    
    
    /**
     * Récupère les informations d'un lieu en fonction d'une adresse
     * @param $adresse (string) adresse d'un lieu
     * @return moove\ActiviteBundle\Entity\Lieu
     */
    private function getInfosAdresse($adresse)
    {
        
        // On créé un objet GoogleMapsGeocoder prenant en paramètre l'adresse du lieu $adresse
        $geocodeLieu = new \GoogleMapsGeocoder($adresse);
        // On enregistre le résultat de la requête faite à GoogleMapsAPI pour récupérer les informations du lieu
        $reponse = $geocodeLieu->geocode();
        // On récupère les infos sur le lieu
        $infosLieu = $reponse['results'][0]['address_components'];
        // On récupère la latitude et longitude sur le lieu
        $latLngLieu = $reponse['results'][0]['geometry']['location'];
        
        if(isset($infosLieu[6])) {
            $lieu = new Lieu();
            // On hydrate le lieu avec les données précédemment récupérées
            $lieu->setNom(null)
                 ->setNumeroRue($infosLieu[0]['long_name'])
                 ->setNomRue($infosLieu[1]['long_name'])
                 ->setComplementAdresse(null)
                 ->setCodePostal($infosLieu[6]['long_name'])
                 ->setVille($infosLieu[2]['long_name'])
                 ->setLatitude($latLngLieu['lat'])
                 ->setLongitude($latLngLieu['lng'])
            ;            
        }
        else {
            $lieu = new Lieu();
            // On hydrate le lieu avec les données précédemment récupérées
            $lieu->setNom(null)
                 ->setNumeroRue(null)
                 ->setNomRue($infosLieu[0]['long_name'])
                 ->setComplementAdresse(null)
                 ->setCodePostal($infosLieu[5]['long_name'])
                 ->setVille($infosLieu[1]['long_name'])
                 ->setLatitude($latLngLieu['lat'])
                 ->setLongitude($latLngLieu['lng'])
            ;               
        }
        

        return $lieu;
    }
    //fonction qui permet de passer l'attribut estTerminée de toutes les activitées terminées à true
    private function verificationEstTerminee()
    {
        $repActivite = $this->getRepository('Activite');
        $listeActivite = $repActivite->findAll();
        $em = $this->getDoctrine()->getManager();
        $dateHeureDuJour = new \DateTime("now");
        foreach ($listeActivite as $activite) {
            if ($activite->getDateHeureRDV() < $dateHeureDuJour)
            {
                $activite->setEstTerminee(true);
                $em->persist($activite);
            }
            $em->flush();
        }
    }
    /**
     * simplifie : $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:MonRepository');
     * en $this->getRepository('MonRepository'); ou $this->getRepository('MonRepository', 'MonBundle')
     * 
     * @param $nomRepository (string) nom du repository souhaité
     * @param $nomBundle (string = 'Activite') nom du bundle (si différent de celui actuel)
     * @return (repository)
     */
    private function getRepository($nomRepository, $nomBundle = 'Activite')
    {
        return $this->getDoctrine()->getManager()->getRepository('moove'.$nomBundle.'Bundle:'.$nomRepository);
    }
    
    //private function get
} // fin de "class ActivitesController extends Controller"