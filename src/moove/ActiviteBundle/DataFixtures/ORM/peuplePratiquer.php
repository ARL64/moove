<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Pratiquer;

class PeuplerPratiquer implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
       /* $cyclisme = new Sport();
		$cyclisme   ->setNom("Cyclisme")
		            ->setUrlPictogramme("A_CHANGER");
        $manager->persist($cyclisme);*/

        
        
        $manager->flush();
    }
}
?>