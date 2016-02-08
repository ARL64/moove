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
        
        $file = fopen(__DIR__ . "/peuplerPratiquer.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Pratiquer();
            $temps->setUtilisateur($this->getReference('utilisateur-' . $line[1]))
		          ->setSport($this->getReference('sport-' . $line[2]))
		          ->setNiveau($this->getReference('niveau-' . $line[3]))
            ;
		            
            $manager->persist($temps);
            $this->addReference("pratiquer-" . $line[0], $temps);
            
        }
        fclose($file);
/*        
        // Jsarie
        // -------------------------------------------------------------------------------------        
        $cyclismeJsarie = new Pratiquer();
		$cyclismeJsarie ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-intermediaire'))
		                ;
        $manager->persist($cyclismeJsarie);
        $this->addReference('pratiquer-cylismeJsarie', $cyclismeJsarie);
        //$this->getReference('utilisateur-jsarie')->addPratiquer($cyclismeJsarie);
        // -------------------------------------------------------------------------------------        
        /*$joggingJsarie = new Pratiquer();
		$joggingJsarie  ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($joggingJsarie);
        $this->addReference('pratiquer-joggingJsarie', $joggingJsarie);
        $this->getReference('utilisateur-jsarie')->addPratiquer($joggingJsarie);*/
        // -------------------------------------------------------------------------------------        
/*        $randonneJsarie = new Pratiquer();
		$randonneJsarie ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneJsarie);
        $this->addReference('pratiquer-randonneJsarie', $randonneJsarie);
        //$this->getReference('utilisateur-jsarie')->addPratiquer($randonneJsarie);
        // -------------------------------------------------------------------------------------        
        $skiJsarie = new Pratiquer();
		$skiJsarie ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-expert'))
		                ;
        $manager->persist($skiJsarie);
        $this->addReference('pratiquer-skiJsarie', $skiJsarie);
        //$this->getReference('utilisateur-jsarie')->addPratiquer($skiJsarie);
        // -------------------------------------------------------------------------------------        

        // Avauthey
        // -------------------------------------------------------------------------------------        
        $cyclismeAvauthey = new Pratiquer();
		$cyclismeAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-intermediaire'))
		                ;
        $manager->persist($cyclismeAvauthey);
        $this->addReference('pratiquer-cylismeAvauthey', $cyclismeAvauthey);
        //$this->getReference('utilisateur-avauthey')->addPratiquer($cyclismeAvauthey);
        // -------------------------------------------------------------------------------------        
        $joggingAvauthey = new Pratiquer();
		$joggingAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-expert'))
		                ;
        $manager->persist($joggingAvauthey);
        $this->addReference('pratiquer-joggingAvauthey', $joggingAvauthey);
        $this->getReference('utilisateur-avauthey')->addPratiquer($joggingAvauthey);
        // -------------------------------------------------------------------------------------        
        /*$randonneAvauthey = new Pratiquer();
		$randonneAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneAvauthey);
        $this->addReference('pratiquer-randonneAvauthey', $randonneAvauthey);
        $this->getReference('utilisateur-avauthey')->addPratiquer($randonneAvauthey);
        // -------------------------------------------------------------------------------------        
        $skiAvauthey = new Pratiquer();
		$skiAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($skiAvauthey);
        $this->addReference('pratiquer-skiAvauthey', $skiAvauthey);
        $this->getReference('utilisateur-avauthey')->addPratiquer($skiAvauthey);*/
        // -------------------------------------------------------------------------------------        

        // Jmpichon
        // -------------------------------------------------------------------------------------        
/*        $cyclismeJmpichon = new Pratiquer();
		$cyclismeJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-intermediaire'))
		                ;
        $manager->persist($cyclismeJmpichon);
        $this->addReference('pratiquer-cylismeJmpichon', $cyclismeJmpichon);
        $this->getReference('utilisateur-jmpichon')->addPratiquer($cyclismeJmpichon);
        // -------------------------------------------------------------------------------------        
        $joggingJmpichon = new Pratiquer();
		$joggingJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($joggingJmpichon);
        $this->addReference('pratiquer-joggingJmpichon', $joggingJmpichon);
        //$this->getReference('utilisateur-jmpichon')->addPratiquer($joggingJmpichon);
        // -------------------------------------------------------------------------------------        
        $randonneJmpichon = new Pratiquer();
		$randonneJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneJmpichon);
        $this->addReference('pratiquer-randonneJmpichon', $randonneJmpichon);
        //$this->getReference('utilisateur-jmpichon')->addPratiquer($randonneJmpichon);
        // -------------------------------------------------------------------------------------        
        $skiJmpichon = new Pratiquer();
		$skiJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-expert'))
		                ;
        $manager->persist($skiJmpichon);
        $this->addReference('pratiquer-skiJmpichon', $skiJmpichon);
        //$this->getReference('utilisateur-jmpichon')->addPratiquer($skiJmpichon);
        // -------------------------------------------------------------------------------------        

        // Fdartigues
        // -------------------------------------------------------------------------------------        
        $cyclismeFdartigues = new Pratiquer();
		$cyclismeFdartigues ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-intermediaire'))
		                ;
        $manager->persist($cyclismeFdartigues);
        $this->addReference('pratiquer-cylismeFdartigues', $cyclismeFdartigues);
        //$this->getReference('utilisateur-fdartigues')->addPratiquer($cyclismeFdartigues);
        // -------------------------------------------------------------------------------------        
        $joggingFdartigues = new Pratiquer();
		$joggingFdartigues ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-expert'))
		                ;
        $manager->persist($joggingFdartigues);
        $this->addReference('pratiquer-joggingFdartigues', $joggingFdartigues);
        //$this->getReference('utilisateur-fdartigues')->addPratiquer($joggingFdartigues);
        // -------------------------------------------------------------------------------------        
       /* $randonneFdartigues = new Pratiquer();
		$randonneFdartigues ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneFdartigues);
        $this->addReference('pratiquer-randonneFdartigues', $randonneFdartigues);
        $this->getReference('utilisateur-fdartigues')->addPratiquer($randonneFdartigues);*/
        // -------------------------------------------------------------------------------------        
/*        $skiFdartigues = new Pratiquer();
		$skiFdartigues  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-expert'))
		                ;
        $manager->persist($skiFdartigues);
        $this->addReference('pratiquer-skiFdartigues', $skiFdartigues);
        //$this->getReference('utilisateur-fdartigues')->addPratiquer($skiFdartigues);
        // -------------------------------------------------------------------------------------        
*/
        
        
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
}
?>