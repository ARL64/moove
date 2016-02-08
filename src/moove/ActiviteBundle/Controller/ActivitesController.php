<?php

namespace moove\ActiviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use moove\ActiviteBundle\Entity\Activite;
use moove\ActiviteBundle\Entity\Sport;
use moove\ActiviteBundle\Entity\Lieu;
use moove\ActiviteBundle\Entity\Participer;
use Symfony\Component\HttpFoundation\Request;
use \GeocodeMapsGeocoder;
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
        $this->checkAuthorization();
        //on récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        /** Repository de Participer */
        $repParticipations = $this->getRepository('Participer');
        /** Repository de Activite */
        $repActivite = $this->getRepository('Activite');

        $listeOrganisationEnApproche = $repParticipations->findByUtilisateurEstAccepter($utilisateur, false, 1);
        $nbOrganisations = count($repActivite->findBy(array('organisateur' => $utilisateur, 'estTerminee' => 0)));
        
        
        $listeDesDemandesDeParticipationsEnAttente = $repParticipations->findByOrganisateur($utilisateur, 0);
        $nbDemandesParticipationsActiviteEnAttente = count($listeDesDemandesDeParticipationsEnAttente);

        $listeParticipationEnApproche = $repParticipations->findByUtilisateurEstAccepter($utilisateur, false, 1);
        $nbParticipations = count($listeParticipationEnApproche) - $nbOrganisations;
        
        $listeDesDemandesEnAttente = $repActivite->findByUtilisateurAccepter($utilisateur, 0);
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
        $this->checkAuthorization();
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
         
         $arg = $estOrganisateur? array(1,0) : 1;   
         
        // On récupère un tableau d'objet Participer $tabParticiper
        $tabParticiper = $repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 1));
        // On récupère le nombre de participants de l'activité
        $nbParticipants = count($tabParticiper);
        
        if($estOrganisateur)
                $tabParticiper = array_merge($tabParticiper, $repParticiper->findBy(array('activite' => $idActivite, 'estAccepte' => 0)));

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
    public function historiqueAction()
    {   
        $this->checkAuthorization();
        
        $utilisateur = $this->getUser(); 
        $repActivite = $this->getRepository('Activite');
        $tabActivites = $repActivite->findByUtilisateur($utilisateur->getId(), true);
        /** Liste du nombre de participations */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordHistorique.html.twig',
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/activites"
     * 
     * @param $page (integer) numéro de la page actuel 
     */
    public function enCoursAction()
    {   
        $this->checkAuthorization();

        /** liste des activités de l'utilisateur courrant*/
        $utilisateur = $this->getUser(); 
        $repActivite = $this->getRepository('Activite');
        $tabActivites = $repActivite->findByUtilisateur($utilisateur->getId(), false);        /** liste du nombre de participant pour chaque activité en corélation avec $tabActivite */
        $tabNbParticipants = $this->getNbParticipantsParActivite($tabActivites);
        $tabEstAccepte = [];
        foreach($tabActivites as $activite)
        {
            $tabEstAccepte[] = $this->estAccepte($activite);
        }
        
        return $this->render('mooveActiviteBundle:Accueil:tableauDeBordActivites.html.twig', 
                            array('tabActivites' => $tabActivites, 'tabNbParticipants' => $tabNbParticipants, 'tabEstAccepte' => $tabEstAccepte));
    }
    
    /**
     * Renvoie à la page "http://moove-arl64.c9users.io/web/app_dev.php/rechercher"
     * 
     */
    public function rechercherActiviteAction()
    {
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');
        // On récupère toutes les activités dans $tabActivites
        $tabActivites = $repActivite->findAll();
        // On compte combien il y a d'activités
        $nbActivites = count($tabActivites);
        
        return $this->render('mooveActiviteBundle:Activite:rechercherActivites.html.twig', array(
            'tabActivites' => $tabActivites,
            'nbActivites' => $nbActivites
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
                    ->setDateCreation(new \Datetime("NOW"))
                    ->setEstTerminee(false)
                ;

        // On crée le formulaire permettant de saisir un livre
        $formulaireActivite = $this->createFormBuilder($activite)
                                    ->add('sportPratique', 'entity',
                                          array('label' => 'Sport',
                                                'empty_value' => 'Sélectionnez un sport',
                                                'class' => 'mooveActiviteBundle:Sport',
                                                'property' => 'nom',
                                                'multiple' => false,
                                                'expanded' => false))
                                    ->add('niveauRequis', 'entity',
                                          array('label' => 'Niveau requis',
                                                'empty_value' => 'Sélectionez un niveau',
                                                'class' => 'mooveActiviteBundle:Niveau',
                                                'property' => 'libelle',
                                                'multiple' => false,
                                                'expanded' => false))
                                   //->add('lieuRDV')
                                   //->add('lieuDepart')
                                   //->add('lieuArrivee')
                                   ->add('dateHeureRDV', 'datetime', array('label' => 'Date et heure de rendez-vous','years'=>range($annee, ($annee+5))))
                                   ->add('dateFermeture', 'datetime', array('label' => 'Date et heure de fermeture  de l\'activité', 'years'=>range($annee, ($annee+5))))
                                   ->add('duree', 'time', array('label' => 'Durée estimée'))
                                   ->add('nbPlaces','integer', array('label'=> 'Nombre de places total (vous inclus)'))
                                   ->add('description', 'textarea', array ('label' => 'Informations'))
                                   ->add('adresseLieuRDV', 'text')
                                   ->getForm();
                                   
        /* On analyse la requête courante pour savoir si le formulaire a été soumis ou pas.
        Dans le cas d'une soumission, les données saisies par l'utilisateur viendront remplir
        l'objet $activite*/
        $formulaireActivite->handleRequest($requeteUtilisateur);
        
        if($formulaireActivite->isSubmitted()) // Le formulaire a été soumis
        {
            // On récupère l'adresse du lieu de rendez-vous dans $adresseLieuRDV
            $adresseLieuRDV = $formulaireActivite->getData()->getAdresseLieuRDV();
            
            // On créé un objet GoogleMapsGeocoder prenant en paramètre l'adresse du lieu de rendez-vous $adresseLieuRDV
            $geocodeLieuRDV = new \GoogleMapsGeocoder($adresseLieuRDV);
            // On enregistre le résultat de la requête faite à GoogleMapsAPI pour récupérer les informations du lieu
            $reponse = $geocodeLieuRDV->geocode();
            // On récupère les infos sur le lieu
            $infosLieuRDV = $reponse['results'][0]['address_components'];
            // On récupère la latitude et longitude sur le lieu
            $latLngLieuRDV = $reponse['results'][0]['geometry']['location'];
            // On hydrate le lieu avec les données précédemment récupérées
            $lieuRDV->setNom(null)
                    ->setNumeroRue($infosLieuRDV[0]['long_name'])
                    ->setNomRue($infosLieuRDV[1]['long_name'])
                    ->setComplementAdresse(null)
                    ->setCodePostal($infosLieuRDV[6]['long_name'])
                    ->setVille($infosLieuRDV[2]['long_name'])
                    ->setLatitude($latLngLieuRDV['lat'])
                    ->setLongitude($latLngLieuRDV['lng'])
            ;
            // On appelle le gestionnaire d'entité
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            
            // On persiste le lieu dans la base de données
            $gestionnaireEntite->persist($lieuRDV);
            
            // On ajoute le lieu à l'activité
            $activite->setLieuRDV($lieuRDV);
            
            // On créé un objet Participer
            $participer = new Participer();
            
            // On remplit l'objet Participer avec l'activité et l'utilisateur organisateur
            $participer->setActivite($activite)
                       ->setUtilisateur($this->getUser())
                       ->setEstAccepte(true);
            
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
        $this->demandeParticipation($idActivite, $idUtilisateur, 1);
    }
    
    public function refuserDemandeParticipationActiviteAction($idActivite, $idUtilisateur)
    {
        $this->demandeParticipation($idActivite, $idUtilisateur, 2);
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
        //$user = $this->getRepository('Utilisateur', 'Utilisateur')->find($idUtilisateur);
        $utilisateur = $this->getUser();
        
        // On récupère le repository participer
        $repParticiper = $this->getRepository('Participer');
        $quitterActivite = $repParticiper->quitterActivite($idActivite, $utilisateur);
        return $this->redirect($this->generateUrl('moove_activite_detailsActivite', array ('idActivite'=> $idActivite)));
        
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
	        $listeParticipant = $repParticiper->findBy(array('activite' => $activite, 'estAccepte' => 1));
            $listeNbParticipant[] = count($listeParticipant);
        }
        
        return $listeNbParticipant;
    }
    
    protected function getNbParticipantsActivite($idActivite)
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
    protected function estParticipantDeActivite($activite)
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
    protected function estAccepte($activite)
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
    
    protected function estOrganisateur($activite)
    {
        // On récupère l'utilisateur connecté
        $utilisateur = $this->getUser();
        
        // On récupère le repository Activite
        $repActivite = $this->getRepository('Activite');

        return ($repActivite->find($activite)->getOrganisateur() == $utilisateur);
    }
    
    protected function demandeParticipation($idActivite, $idUtilisateur, $accepte)
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
                
                // On ajoute un message flash à la session afin de notifier l'utilisateur
                if($accepte == 1)
                    $this->get('session')->getSession()->getFlashBag()->add('notice', "L'utilisateur a bien été accepté !");
                if($accepte == 2)
                    $this->get('session')->getSession()->getFlashBag()->add('notice', "L'utilisateur a bien été refusé !");
                return $this->redirect($this->generateUrl('moove_activite_detailsActivite', ['idActivite' => $activite->getId()]));
        
            }
            
            $requeteUtilisateur->getSession()->getFlashBag()->add('notice', "La data limite d'inscription est dépassée ou il n'y a plus de places disponibles dans l'activité.");
            return $this->redirect($this->generateUrl('moove_activite_detailsActivite', ['idActivite' => $activite->getId()]));
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
    protected function getRepository($nomRepository, $nomBundle = 'Activite')
    {
        return $this->getDoctrine()->getManager()->getRepository('moove'.$nomBundle.'Bundle:'.$nomRepository);
    }
} // fin de "class ActivitesController extends Controller"


