<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Activite;

class PeuplerActivite implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dateActuel = new \DateTime('NOW');
        $duree = new \DateTime('NOW');
        $duree->add(new \DateInterval('PT1H30M'));
        
        $activite = new Activite();
		$activite   ->setDateHeureRDV(new \DateTime('2016-01-13 8:00:00'))
		            ->setDuree($duree)
		            ->setNbPLaces(10)
		            ->setDescription("Petite virée OKLM au bord de la nive")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-01-13 12:00:00'))
		            ->setEstTerminee(false)
		            //->setOrganisateur("Mouloud")
		            //->setNiveauRequis(1)
		            //->setSportPratique(1)
		            ;
        $manager->persist($activite);
        
        $activite2 = new Activite();
		$activite2  ->setDateHeureRDV(new \DateTime('2016-01-16 13:00:00'))
		            ->setDuree($duree)
		            ->setNbPLaces(10)
		            ->setDescription("Go surfer! même si le sport existe pas...")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-01-16 16:00:00'))
		            ->setEstTerminee(false)
		            //->setOrganisateur("LoudMou")
		            //->setNiveauRequis(1)
		            //->setSportPratique(1)
		            ;
        $manager->persist($activite2);

        
        
        $manager->flush();
    }
}
?>