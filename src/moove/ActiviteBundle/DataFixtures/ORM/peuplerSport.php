<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Sport;

class PeuplerSport extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // -------------------------------------------------------------------------------------        
        $cyclisme = new Sport();
		$cyclisme   ->setNom("Cyclisme")
		            ->setUrlPictogramme("cyclisme.png");
        $manager->persist($cyclisme);
        $this->addReference('sport-cyclisme', $cyclisme);
        // -------------------------------------------------------------------------------------        
        $jogging = new Sport();
		$jogging    ->setNom("Jogging")
		            ->setUrlPictogramme("jogging.png");
        $manager->persist($jogging);
        $this->addReference('sport-jogging', $jogging);
        // -------------------------------------------------------------------------------------        
        $randonne = new Sport();
		$randonne   ->setNom("Randonné")
		            ->setUrlPictogramme("randonné.png");
        $manager->persist($randonne);
        $this->addReference('sport-randonne', $randonne);
        // -------------------------------------------------------------------------------------        
        $ski = new Sport();
		$ski    ->setNom("Ski")
		        ->setUrlPictogramme("Sks.png");
        $manager->persist($ski); 
        $this->addReference('sport-ski', $ski);
        // -------------------------------------------------------------------------------------        

        
        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }
}
?>