<?php
namespace moove\ActiviteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateActivitesCommand extends ContainerAwareCommand
{
    //On crée une commande 
    protected function configure()
    {
        $this->setName('moove:update:activites');
    }

    /**
     * Commande executé régulièrement par le CRON
     * @param $input <i>InputInterface</i>
     * @param $output <i>OutputInterface</i>
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        // On récupère le répository de particper et activité
        $repParticiper = $em->getRepository('mooveActiviteBundle:Participer');
        $repActivite = $em->getRepository('mooveActiviteBundle:Activite');
        //On récupère toutes les activités
        $listeActivite = $repActivite->findAll();
       //On récupère la date du jour
        $dateHeureDuJour = new \DateTime("now");
        //On parcours toutes les activités
        foreach ($listeActivite as $activite) {
            // Si l'heure du jour est dépassée
            if ($activite->getDateHeureRDV() < $dateHeureDuJour)
            {
                // On récupère un tableau de particpation en fonction de l'idée de l'activité courante
                $listeParticipation = $repParticiper->findBy(array('activite' => $activite));
                // on parcours le tableau de participation 
                foreach($listeParticipation as $participation)
                {
                    // si l'attribut est accepté est à 0 (utilisateur non accepté)
                    if($participation->getEstAccepte() == 0)
                    {
                        // on le passe à 2 (utilisateur refusé)
                        $participation->setEstAccepte(2);
                        //On enregistre la table Participation
                        $em->persist($participation);
                    }
                }
                // On termine l'activité en passant l'attribut estTerminée à faux
                $activite->setEstTerminee(true);
                //On enregistre la table Activité
                $em->persist($activite);
            }
        }
        //on envoie les données
        $em->flush();
    }
}