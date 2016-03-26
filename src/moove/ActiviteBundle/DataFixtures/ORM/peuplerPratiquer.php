<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Pratiquer;

class PeuplerPratiquer extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $index = 0;
        $file = fopen(__DIR__ . "/peuplerPratiquer.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Pratiquer();
            $temps->setUtilisateur($this->getReference('utilisateur-' . $line[0]))
		          ->setSport($this->getReference('sport-' . $line[1]))
		          ->setNiveau($this->getReference('niveau-' . $line[2]))
            ;
		            
            $manager->persist($temps);
            $this->addReference("pratiquer-" . $index, $temps);
            $index++;
        }
        fclose($file);
        
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
}
?>