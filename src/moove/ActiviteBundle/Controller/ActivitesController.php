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

use moove\ActiviteBundle\Entity\Commentaire;

require_once __DIR__ . '/../../../../vendor/jstayton/google-maps-geocoder/src/GoogleMapsGeocoder.php';

class ActivitesController extends Controller
{
    // /!\ Actions métier
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/accueil"
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Accueil:tableauDeBordAccueil.html.twig
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
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Activite:detailsActivite.html.twig
     */
    public function detailsActiviteAction($idActivite, Request $request)
    {
        $utilisateur = $this->getUser();

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
        /** Repository de Niveau */
        $repCommentaire = $this->getRepository('Commentaire'); 
        
        // On récupère l'activité par l'id de l'activite $idActivite
        $activite = $repActivite->findWhitDetail($idActivite);

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
        //$tabParticiper = $repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 1), array('id' => 'DESC'));
        $tabParticiper = $repParticiper->findByActiviteAccepter($idActivite, 1, 'p.id', 'DESC');
        // On récupère le nombre de participants de l'activité
        $nbParticipants = count($tabParticiper);
        
        // si l'utilisateur courrant est l'organisateur de l'activité, alors on ajoute la liste des utilisateus en attente.
        if($estOrganisateur)
                $tabParticiper = array_merge($repParticiper->findByActiviteAccepter($idActivite, 0), $tabParticiper);
                //$tabParticiper = array_merge($repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 0)), $tabParticiper);

        // on récolte tous les commentaires disponible 
        $tabCommentaire = $repCommentaire->findBy(array('activite' => $activite), array('posteA' => 'DESC'));
        
        // On indique si l'utilisateur accédant au détails de l'activité est participant ou non
        $resultat = new Commentaire;
        $formulaireCommentaire = $this  ->get('form.factory')->createBuilder('form', $resultat)
                                        ->add('contenu', 'ckeditor', array ('required' => false, 'config_name' => 'config_description'))
                                        ->add('publier', 'submit', array ('label' => 'Publier'))
                                        ->getForm();

        $formulaireCommentaire->handleRequest($request);

        if ($formulaireCommentaire->isSubmitted() && $formulaireCommentaire->isValid())
        {
            var_dump($resultat);
            //$resultat = $formulaireCommentaire->getCommentaire();
            $resultat   //->setContenu($formulaireCommentaire)
                        ->setPosteA(new \DateTime("NOW"))
                        ->setType("")
                        ->setActivite($activite)
                        ->setUtilisateur($utilisateur)
            ;
            
            $ge = $this->getDoctrine()->getManager();
            $ge->persist($resultat);
            $ge->flush();
            
            return $this->redirect($this->generateUrl('moove_activite_detailsActivite', array('idActivite' => $idActivite)));
        }

        return $this->render('mooveActiviteBundle:Activite:detailsActivite.html.twig', array('activite' => $activite, 
                                                                                            'tabParticipants' => $tabParticiper,
                                                                                            'niveauOrganisateur' => $niveauOrganisateur,
                                                                                            'nbParticipants' => $nbParticipants,
                                                                                            'estParticipant' => $estParticipant,
                                                                                            'estAccepte' => $estAccepte,
                                                                                            'estOrganisateur' => $estOrganisateur,
                                                                                            'tabCommentaire' => $tabCommentaire,
                                                                                            'form' => $formulaireCommentaire->createView() ));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/historique"
     * 
     * @param $page <i>(integer)</i> numéro de la page actuel 
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig
     */
    public function historiqueAction(Request $request)
    {   
        // On récupère l'utilisateur
        $utilisateur = $this->getUser(); 
        
        $request = Request::createFromGlobals();
        // on obtiet ici les valeurs des variables de l'URL (situer apres ".php?")
        $order = $this->getOrderBy($request->query->get('order'));
        $type = $request->query->get('type');
        if(is_null($type))
            $type = "DESC";
        // définis le nombre de résultat affiché par page
        $nbResultatsParPage = $request->query->get('nbResultatsParPage');
        if(is_null($nbResultatsParPage)) {
            $nbResultatsParPage = 10;
        }
        else {
            $nbResultatsParPage = $request->query->get('nbResultatsParPage');
        }
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        // On récupère les activités où l'utilisateur connecté est accepté à l'activité
        $findTabActivites = $repActivite->findByUtilisateurAccepter($utilisateur->getId(), 1, true, $order, $type);
        // On récupère les activités pagninées avec 2 éléments par page
        $tabActivites  = $this->get('knp_paginator')->paginate($findTabActivites, $request->query->getInt('page', 1), $nbResultatsParPage);
        /** Liste du nombre de participations */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig', array(
                                    'tabActivites' => $tabActivites, 
                                    'tabNbParticipants' => $tabNbParticipants,
                                    'nbResultatsParPage' => $nbResultatsParPage));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activites"
     * 
     * @param $page <i>(integer)</i> numéro de la page actuel 
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig
     */
    public function enCoursAction(Request $request)
    {

        /** liste des activités de l'utilisateur courrant*/
        $utilisateur = $this->getUser();
        
        $request = Request::createFromGlobals();
        
        
        // on obtiet ici les valeurs des variables de l'URL (situer apres ".php?")
        $order = $this->getOrderBy($request->query->get('order'));
        $type = $request->query->get('type');
        if(is_null($type))
            $type = "DESC";
        // définis le nombre de résultat affiché par page
        
        $nbResultatsParPage = $request->query->get('nbResultatsParPage');
        if(is_null($nbResultatsParPage)) {
            $nbResultatsParPage = 10;
        }
        else {
            $nbResultatsParPage = $request->query->get('nbResultatsParPage');
        }
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        $repParticiper = $this->getRepository('Participer'); // temps
        // On récupère les activités de l'utuilisateur $utilisateur lorsque l'activité est terminée
        $findTabActivites = $repActivite->findByUtilisateur($utilisateur->getId(), false, $order, $type);        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
        $tabNbParticipants = $this->getNbParticipantsParActivite($findTabActivites);

        // on initiqlise un tableau vide
        $tabEstAccepte = [];
        foreach($findTabActivites as $activite)
        {
            // puis on le remplis en ajoutant a chaque fois le résultat de la fonction.
            // Si le résultat est permet de retouurner le résultat de la valeur de la table participé (optimisable)
            $tabEstAccepte[] = $this->estAccepte($activite);
        }
        
        // on créer un objet Paginator pour le bundle de pagination  
        $tabActivites  = $this->get('knp_paginator')->paginate($findTabActivites, $request->query->getInt('page', 1), $nbResultatsParPage);
        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array(  'tabActivites' => $tabActivites, 
                                    'tabNbParticipants' => $tabNbParticipants, 
                                    'tabEstAccepte' => $tabEstAccepte,
                                    'nbResultatsParPage' => $nbResultatsParPage));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/rechercher"
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Activite:rechercherActivites.html.twig
     */
    public function rechercherActiviteAction()
    {
        $request = Request::createFromGlobals();
        
        // On obtient la liste des sport (pour la génération de la liste des sports, donc trié dans l'ordre ASC)
        $repSport = $this->getRepository('Sport');
        $tabSport = $repSport->findBy(array(), array('nom'=>'asc'));

        // On obtient la liste des niveaux (pour la génération de la liste des sports, donc trié dans l'ordre ASC)
        $repNiveau = $this->getRepository('Niveau');
        $tabNiveau = $repNiveau->findBy(array(), array('libelle'=>'asc'));
        
        if(!empty($_POST))
        {
            // l'information d'un slider est recu sous la forme d'un string avec les deux valeur
            // on explode donc notre string et on récupère les tableau correspondant avec [0] = 1er valeur (min) et [1] = 2nd valeur (max)
            $tabHeure = explode(";", $_POST['heure']);
            $nbPlaceTab = explode(";", $_POST['nbPlaces']);
            // on vérifie que la date est étais remplis. (étant donnée qu'elle peu ne pas être renseigné.)
            if($_POST['date'] != "")
            { 
                $tabDate = explode("/", $_POST['date']);
                $datePrecise = $tabDate[2] . "-" . $tabDate[1] . "-" . $tabDate[0];
            }
            else
            {
                $datePrecise = null;
            }
            
            // on ajoute l'heure selon le format définis dans la fonction de recherche
            $heureMin = $tabHeure[0]. "h00";
            $heureMax = $tabHeure[1]. "h00";
            
            //var_dump($heureMin);

            // on initialise un boolean a false. Si on ne trouve pas de sport, notre variable passera a null pour éviter d'ajouter une condition inutile.
            $sportSelected = false;
            // la structure de "l'array" est : "[spor1,sport2,sport3]"
            $arraySport = "[";
            foreach($tabSport as $sport)
            {
                // si la valeur existe dans la variable $_POST, alors elle cela signfie qu'elle est coché, et donc que l'utilisateur la demande.
                if(array_key_exists('name_'.$sport->getNom(), $_POST))
                {
                    $arraySport .= $sport->getNom() . ",";
                    // si l'on a trouver au moins un sport on change notre varaible a true pour éviter de mettre a null notre variable
                    $sportSelected = true;
                }
            }
            // en fonction du boolean on définis alors la suit. Si true, alors on ferme notre array string, sinon on met a null.
            if($sportSelected)
                $arraySport = substr($arraySport, 0, strlen($arraySport)-1) . "]";
            else 
                $arraySport = null;
            $sport = $arraySport;
            
            // idem pour les niveau
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
            
            // on récupère l'information de photo. la valeurs est accorder directement dans le html dans la baslie "value"
            $photo = $_POST['photo'];
            // on définis ici les place ne fonction des slider. 
            // dans le premier cas, le slider a une seul valeurs donc 
            $nbPlaceRestante = intval($_POST['nbPlacesRestantes']);
            // ici, on récupère les valeurs depuis  le tableau déjà créer avant
            $nbPlaceMin = intval($nbPlaceTab[0]);
            $nbPlaceMax = intval($nbPlaceTab[1]);
            // comme premier cas
            $distanceMax = intval($_POST['rayonRecherche']);
            
            // on passe par la fonction getOrder by qui convertie notre mot clé en syntaxe de base de donnée
            $order = $this->getOrderBy($_POST['order']);
            $type = null;//$request->query->get('type'); // TODO
            if(is_null($type))
                $type = "ASC";
        }
        else 
        {
            $datePrecise = $request->query->get('date');                    // format : 2016-04-14 (YYYY-mm-dd) (date)
            $heureMin = $request->query->get('hMin');                       // format : 07h30 (HHhmm) (date) (nécéssite datePrécise)
            $heureMax = $request->query->get('hMax');                       // format : 14h45 (HHhmm) (date) (nécéssite datePrécise)
            $sport = $request->query->get('sport');                         // format : [Cyclisme,Jogging,Ski,Randonee] (array)
            $niveau = $request->query->get('niveau');                       // format : [Tous,Debutant,Intermediaire,Confirme] (array)
            $photo = $request->query->get('photo');                         // format : yes (bool)
            $nbPlaceRestante = $request->query->get('nbPlace');             // format : 10  (int)
            $nbPlaceMin = $request->query->get('placeRestanteMin');         // format : 2  (int)
            $nbPlaceMax = $request->query->get('placeRestanteMax');         // format : 7  (int)
            $distanceMax = $request->query->get('distance');                // format : 10  NON DISPONIBLE
            
            $order = $this->getOrderBy($request->query->get('order'));
            $type = $request->query->get('type');
            if(is_null($type))
                $type = "ASC";
        }
        
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
     * @param <i>(Request)</i> ???
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Activite:proposerActivite.html.twig
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
    
    /**
     * Accepte la demande d'un utilisateur concernant sa participation à une activité de l'utilisateur
     * @param <i>(Activite)</i> id de l'activite courrante
     * @param <i>(Utilisateur)</i> id de l'utilisateur concerner
     * @return <i>Render</i> redirige sur moove_activite_detailsActivite + activite actuel
     */
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
    
    /**
     * Refuse la demande d'un utilisateur concernant sa participation à une activité de l'utilisateur
     * @param <i>(Activite)</i> id de l'activite courrante
     * @param <i>(Utilisateur)</i> id de l'utilisateur concerner
     * @return <i>Render</i> redirige sur moove_activite_detailsActivite + activite actuel
     */
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
    
    /**
     * Envoie une demande de participation a l'activité courrante
     * @param <i>(Activite)</i> id de l'activite courrante
     * @param <i>(Utilisateur)</i> id de l'utilisateur concerner
     * @return <i>Render</i> redirige sur moove_activite_detailsActivite + activite actuel
     */
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
      
    /**
     * Quitte l'activité courrante
     * @param <i>(Activite)</i> id de l'activite courrante
     * @param <i>(Utilisateur)</i> id de l'utilisateur concerner
     * @return <i>Render</i> redirige sur moove_activite_detailsActivite + activite actuel
     */  
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
    
    /**
     * Supprime l'activité si, et uniquement si, l'utilisateur qui a engendrer a requête est l'organisateur
     * @param <i>(Activite)</i> id de l'activite courrante
     * @param <i>(Utilisateur)</i> id de l'organisateur
     * @return <i>Render</i> redirige sur moove_activite_tableauDeBord
     */
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
    
    /**
     * Modifie l'activite actuel
     * @param <i>(Request)</i> ???
     * @param <i>(Utilisateur)</i> id de l'organisateur
     * @return <i>Render</i> redirige sur mooveActiviteBundle:Activite:modifierActivite.html.twig
     */
    public function modifierActiviteAction(Request $requeteUtilisateur, $idActivite)
    {
        $utilisateur = $this->getUser();
        $activite = $this->getRepository('Activite')->find($idActivite);
        if($activite->getOrganisateur() == $utilisateur)
        {
            $formulaireActivite = $this->createForm(new ActiviteType, $activite);
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
                
                //On enregistre l'objet $activite en base de données
                $gestionnaireEntite->persist($activite);
                $gestionnaireEntite->flush();
                // $this->get('session')
                $requeteUtilisateur->getSession()->getFlashBag()->add('notice', 'Activité modifiée.');
                //On redirige vers la page de visualisation de l'activité ajouté
                return $this->redirect($this->generateUrl('moove_activite_detailsActivite',
                                                          array('idActivite' => $activite->getId())));
            }
            //A ce point, le visiteur arrive sur la page qui doit afficher le formulaire
            return $this->render('mooveActiviteBundle:Activite:modifierActivite.html.twig',
                                 array('formulaireActivite' => $formulaireActivite->createView()));
        }
        else
        {
           $this->addFlash('notice', "Vous ne pouvez pas faire ça !");
            return $this->redirect($this->generateUrl('moove_activite_detailsActivite', array('idActivite' => $idActivite)));
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
     * @param $tabActivites <i>(array<Activite>)</i> Tableau contenant des activités
     * @return <i>array<Integer></i>
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
    
    /**
     * Retourne le nombre de participant accepter à l'activité courrante
     * 
     * @param $idActivite <i>(Activite)</i> ide de l'activite courrante.
     * @return <i>Integer</i>
     */
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
     * Retourne un booléen permettant d'indiquer si l'utilisateur connecté participe à l'activité $idActivite (cela inclut refusé et en attente)
     * 
     * @param $idActivite <i>(Activite)</i> id de de l'activité concernée
     * @return <i>boolean</i> true si l'utilisateur participe à l'activité, false sinon.
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
     * Retourne un booléen permettant d'indiquer si l'utilisateur connecté participe à l'activité $idActivite (cela n'inclut que les participation accepté)
     * @param $activite <i>(integer)</i> id de l'activité souhaite
     * @return <i>Integer</i> 0 si l'utilisateur n'es pas accepter, 1 si il est accepter, 2 si il est refuser
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
 
    /**
     * Retourne un booléen permettant d'indiquer si l'utilisateur courrant est l'organisateur de l'activité
     * 
     * @param $idActivite <i>(Activite)</i> id de de l'activité concernée
     * @return <i>boolean</i> true si l'utilisateur est l'organisateur, false sinon.
     */   
    private function estOrganisateur($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');

        return ($repActivite->find($activite)->getOrganisateur() == $utilisateur);
    }
    
    
    /**
     * Effectue la mise à mise à jour de l'état d'une demande
     * 
     * @param $idActivite <i>(Activite)</i> id de de l'activité concerné
     * @param $idUtilisateur <i>(Utilisateur)</i> id de de l'utilisateur concerné
     * @param $accepte <i>(Integer)</i> nouvel état de la demande
     * @return <i>boolean</i> true si la mise a jour a eu lieu, false sinon
     */
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
     * @param $adresse <i>(string)</i> adresse d'un lieu
     * @return <i>moove\ActiviteBundle\Entity\Lieu</i>
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

   /**
     * renvois la structure nécessaire a la condition "where" dans les requetes 
     * et ceux en fonction du mot clé rece
     * @param $order <i>(String)</i> mot clé définissant la strucuture
     * @return <i>String</i> chemin d'acces à la donnée dans la base.
     */
    public function getOrderBy($order)
    {
        // Afin d'évité d'afficher la strucuture de la base dans l'url, on transmet un mot clé.
        switch ($order) 
        {
            case 'sport':
                $temps = "s.nom";
                break;
            case 'niveau':
                $temps = "a.niveauRequis";
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
            default: // par défaut, on trie par date
                $temps = "a.dateHeureRDV";
                break;
        }
        
        // on retourne la strucutre de la base en fonction du mot clé
        return $temps;
    }
    
    /**
     * simplifie : $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:MonRepository');
     * en $this->getRepository('MonRepository'); ou $this->getRepository('MonRepository', 'MonBundle')
     * 
     * @param $nomRepository <i>(string)</i> nom du repository souhaité
     * @param $nomBundle <i>(string = 'Activite')</i> nom du bundle (si différent de celui actuel)
     * @return <i>repository</i>
     */
    private function getRepository($nomRepository, $nomBundle = 'Activite')
    {
        return $this->getDoctrine()->getManager()->getRepository('moove'.$nomBundle.'Bundle:'.$nomRepository);
    }
    
} // fin de "class ActivitesController extends Controller"