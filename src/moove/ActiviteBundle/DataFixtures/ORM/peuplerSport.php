<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Sport;

class PeuplerSport implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cyclisme = new Sport();
		$cyclisme   ->setNom("Cyclisme")
		            ->setUrlPictogramme("A_CHANGER");
        $manager->persist($cyclisme);
        
        $jogging = new Sport();
		$jogging    ->setNom("Jogging")
		            ->setUrlPictogramme("A_CHANGER");
        $manager->persist($jogging);
        
        $randonne = new Sport();
		$randonne   ->setNom("Randonné")
		            ->setUrlPictogramme("A_CHANGER");
        $manager->persist($randonne);
        
        $ski = new Sport();
		$ski    ->setNom("Ski")
		        ->setUrlPictogramme("A_CHANGER");
        $manager->persist($ski); 
        
        
        $manager->flush();
    }
}
?>