<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Niveau;

class PeuplerNiveau extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        // ceci est un test
        $file = fopen(__DIR__ . "/peuplerNiveau.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Niveau();
            $temps ->setLibelle($line[1]);
            $manager->persist($temps);
            $refName = "niveau-" . $line[0];
            $this->addReference($refName, $temps);
            
        }
        fclose($file);
        
        /*
        
        // -------------------------------------------------------------------------------------        
        $debutant = new Niveau();
        $debutant->setLibelle("Débutant");
        $manager->persist($debutant);
        $this->addReference('niveau-debutant', $debutant);
        // -------------------------------------------------------------------------------------        
        $intermediaire = new Niveau();
        $intermediaire->setLibelle("Intermédiaire");
        $manager->persist($intermediaire);
        $this->addReference('niveau-intermediaire', $intermediaire);
        // -------------------------------------------------------------------------------------        
        $confirme = new Niveau();
        $confirme->setLibelle("Confirmé");
        $manager->persist($confirme);
        $this->addReference('niveau-confirme', $confirme);
        // -------------------------------------------------------------------------------------        
        $expert = new Niveau();
        $expert->setLibelle("Expert");
        $manager->persist($expert);
        $this->addReference('niveau-expert', $expert);
        // -------------------------------------------------------------------------------------   */  
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }
}
?>