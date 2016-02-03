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

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller
{
    
    protected function checkAuthorization()
    {
        // Vérifie si l'utilisateur est authentifié 
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) 
        { 
            throw $this->createAccessDeniedException(); 
        }   
    }
    
    /**
     * Show the user
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

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
}
