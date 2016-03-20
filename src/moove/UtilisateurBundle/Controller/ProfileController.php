<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace moove\UtilisateurBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use moove\ActiviteBundle\Entity\Lieu;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller
{
    /**
     * Show the user
     * @return <i>Render</i> redirige sur FOSUserBundle:Profile:show.html.twig
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        //On récupère le manager & le repository de pratiquer, d'activité et de participer
        $repPratiquer = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Pratiquer');
        $repActivite = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Activite');
        $repParticipations = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Participer');
        //On récupère un tableau de pratiquer où il y a l'id de l'utilisateur
        $tabSportNiveau = $repPratiquer->findByUtilisateur($user);
        //$nbSportNiveau = count($repPratiquer->findByUtilisateur($user));
        $nbSportNiveau = count($tabSportNiveau);
        
        //On récupère le nombre d'organisation non terminée
        $nbOrganisations = count($repActivite->findBy(array('organisateur' => $user, 'estTerminee' => 0)));
        // on récupère la liste des participations à venir
        $listeParticipationEnApproche = $repParticipations->findByUtilisateurEstAccepter($user, false, 1);
        //on récupère le nombre de participations à venir
        $nbParticipations = count($listeParticipationEnApproche) - $nbOrganisations;
        
        //On récupère le nombre d'organisation terminées
        $nbOrganisationsFinies = count($repActivite->findBy(array('organisateur' => $user, 'estTerminee' => 1)));
        //On récupère la liste des participations finis
        $listeParticipationsFinies = $repParticipations->findByUtilisateurEstAccepter($user, true, 1);
        //On récupère le nombre de participations finis
        $nbParticipationsFinies = count($listeParticipationsFinies) - $nbOrganisationsFinies;

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
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
     * Edit the user
     * @return <i>Render</i> redirige sur FOSUserBundle:Profile:edit.html.twig
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
            
            // On récupère l'adresse les adresses des lieux
            $adresseLieuResidence = $form->getData()->getAdresseLieuResidence();
                
            // On récupère les infos de chaque lieu dans un nouvel objet
            $lieuResidence = $this->getInfosAdresse($adresseLieuResidence);
            if(!is_null($lieuResidence))
            {
                $em = $this->getDoctrine()->getManager();
                $user->setLieuResidence($lieuResidence);
                $em->persist($lieuResidence);
                $em->flush();
            }
            
            $file = $user->getPhoto();
            
            // si il y a une image déposé
            if(!is_null($file))
            {
                // Genere un nom unique aléatoire avant d'enregistrer le fichier
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                $photoDir = $this->container->getParameter('kernel.root_dir').'/../web/bundles/mooveutilisateur/images/avatars/';
                   
                $dest_x = 0; // Abscisse de la copie (point de début) (pour plus tard, si l'on souhaite recadrer depuis un endroit précis)
                $dest_y = 0; // Ordonnee de la copie (point de début) (pour plus tard, si l'on souhaite recadrer depuis un endroit précis)
                $size = 256; // Taille de l'image final (/!\ IMPORTANT /!\ garder la proportionnalité ! 128px la taille original)
                   
                $source = $this->imageCreateFromAny($file); // celle qui sera copiée

                $destination = imagecreatetruecolor($size, $size); // on creer une image de la taille du cadre à copier
                   
                list($width, $height) = getimagesize($file); // Taille Original de l'image
                $ratio = $size / ($width<$height? $width:$height); // permet d'obtenir le plus petit ratio pour réduire la taille de l'image
                $newWidth = $width * $ratio; // normalement, une de ces deux valeur est forcément égale à $size...
                $newHeight = $height * $ratio;
                                      // Première couche : redimension en X*128 ou 128*X
                $imgTemps = imagecreatetruecolor($newWidth, $newHeight); // on créer une image temporaire (la même forme que l'original, mais en proportion réduite)
                imagecopyresized($imgTemps, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height); // on y copie la 
               
                // Deuxième couche : recadrage a partir de dest_x et dest_y
                imagecopy($destination, $imgTemps, $dest_x, $dest_y, 0, 0, $size, $size); // on copie l'image source dans l'image destination du pixel 0 au pixel 127
                imagepng($destination, $photoDir.$fileName); // on créer l'image final (en png), directement dans le dossier des avatars.
    
                imagedestroy($imgTemps); // Spécial dédicace à Roose ! :D
    
                //$file->move($photoDir, $fileName);
        
                $user->setURLAvatar($fileName); 

            }
            
            $userManager->updateUser($user);

            if (null === ($response = $event->getResponse())) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }
        //On récupère le répository de sport
        $repSport = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Sport');
        //On récupère un tableau de sport
        $tabSport = $repSport->findAll();
        //on récupère le répository de pratiquer
        $repPratiquer = $this->getDoctrine()->getManager()->getRepository('mooveActiviteBundle:Pratiquer');
         //On récupère un tableau de pratiquer où il y a l'id de l'utilisateur
        $tabSportNiveau = $repPratiquer->findByUtilisateur($user);
        //$nbSportNiveau = count($repPratiquer->findByUtilisateur($user));
        $nbSportNiveau = count($tabSportNiveau);
        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'tabSportNiveau' => $tabSportNiveau,
            'nbSportNiveau' => $nbSportNiveau,
            'tabSport' => $tabSport
        ));
    }

    /**
     * 
     * supprime la photo
     * @return <i>Render</i> redirige sur fos_user_profile_edit
     */ 
    public function supprimerPhotoAction()
    {
        // On récupère l'utilisateur
        $user = $this->getUser();
        // On appelle le gestionnaire fos user
        $userManager = $this->get('fos_user.user_manager');
        // On remet son image de profil à default
        $user->setURLAvatar('default.png');

        // On enregistre les changements en BD
        $userManager->updateUser($user);
        
        // On redirige l'utilisateur vers l'édition de son profil
        return $this->redirect($this->generateUrl('fos_user_profile_edit'));
    }

    /**
     * @author Matt Squirrell
     * @source http://php.net/manual/fr/function.imagecreatefromjpeg.php
     * @licence none
     * 
     * @param <i>String</i> url de l'image
     * @return <i>Image</i> copie de l'image
     */
    protected function imageCreateFromAny($filepath) 
    { 
        $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
        $allowedTypes = array( 
            1,  // [] gif 
            2,  // [] jpg 
            3,  // [] png 
            6   // [] bmp 
        ); 
        if (!in_array($type, $allowedTypes)) 
            return "error"; 
        switch ($type) 
        { 
            case 1 : 
                $im = imageCreateFromGif($filepath); 
                break; 
            case 2 : 
                $im = imageCreateFromJpeg($filepath); 
                break; 
            case 3 : 
                $im = imageCreateFromPng($filepath); 
                break; 
            case 6 : 
                $im = imageCreateFromBmp($filepath); 
                break; 
        }    
        return $im;  
    }
    
    /**
     * Récupère les informations d'un lieu en fonction d'une adresse
     * @param $adresse <i>string</i> adresse d'un lieu
     * @return <i>Lieu</i>
     */
    private function getInfosAdresse($adresse)
    {
        if(!is_null($adresse))
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
        }
        else
        {
            $lieu = null;
        }
        return $lieu;
    }
}
