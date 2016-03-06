<?php
namespace moove\ActiviteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateActivitesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('moove:update:activites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repActivite = $em->getRepository('mooveActiviteBundle:Activite');
        $listeActivite = $repActivite->findAll();
        $dateHeureDuJour = new \DateTime("now");
        foreach ($listeActivite as $activite) {
            if ($activite->getDateHeureRDV() < $dateHeureDuJour)
            {
                $activite->setEstTerminee(true);
                $em->persist($activite);
            }
        }
        $em->flush();
    }
}