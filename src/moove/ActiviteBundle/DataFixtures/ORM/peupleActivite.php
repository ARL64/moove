<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use moove\ActiviteBundle\Entity\Activite;

class PeuplerActivite extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        $dateActuel = new \DateTime('NOW');

        // -------------------------------------------------------------------------------------        
        $activite001 = new Activite();
		$activite001->setDateHeureRDV(new \DateTime('2016-01-23 8:00:00'))
		            ->setDuree(new \DateTime('8:30:00'))
		            ->setNbPLaces(3)
		            ->setDescription("Etape 24 du Tour de France 2015")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-01-20 8:00:00'))
		            ->setEstTerminee(false)
		            ->setOrganisateur($this->getReference('utilisateur-jmpichon'))
		            ->setNiveauRequis($this->getReference('niveau-intermediaire'))
		            ->setSportPratique($this->getReference('sport-cyclisme'))
		            ;
        $manager->persist($activite001);
        $this->addReference('activite-001', $activite001);
        // -------------------------------------------------------------------------------------        
        $activite002 = new Activite();
		$activite002->setDateHeureRDV(new \DateTime('2016-04-25 14:00:00'))
		            ->setDuree(new \DateTime('2:00:00'))
		            ->setNbPLaces(5)
		            ->setDescription("Entrainement sportif Haut niveau Jogging")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-04-24 14:00:00'))
		            ->setEstTerminee(false)
		            ->setOrganisateur($this->getReference('utilisateur-fdartigues'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-jogging'))
		            ;
        $manager->persist($activite002);
        $this->addReference('activite-002', $activite002);
        // -------------------------------------------------------------------------------------        
        $activite003 = new Activite();
		$activite003->setDateHeureRDV(new \DateTime('2015-12-16 7:00:00'))
		            ->setDuree(new \DateTime('4:00:00'))
		            ->setNbPLaces(6)
		            ->setDescription("Ski détente - La Mongie")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2015-12-16 6:00:00'))
		            ->setEstTerminee(true)
		            ->setOrganisateur($this->getReference('utilisateur-jsarie'))
		            ->setNiveauRequis($this->getReference('niveau-debutant'))
		            ->setSportPratique($this->getReference('sport-ski'))
		            ;
        $manager->persist($activite003);
        $this->addReference('activite-003', $activite003);  
        // -------------------------------------------------------------------------------------        

        $activite004 = new Activite();
		$activite004->setDateHeureRDV(new \DateTime('2015-12-17 7:00:00'))
		            ->setDuree(new \DateTime('4:00:00'))
		            ->setNbPLaces(6)
		            ->setDescription("Ski Pro - La Mongie")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2015-12-16 6:00:00'))
		            ->setEstTerminee(true)
		            ->setOrganisateur($this->getReference('utilisateur-jsarie'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-ski'))
		            ;
        $manager->persist($activite004);
        $this->addReference('activite-004', $activite004);  
        // -------------------------------------------------------------------------------------        

        $manager->flush();
    }
    
    public function getOrder()
    {
        return 2;
    }
    
    
}
?>