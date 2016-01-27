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
        // Jsarie
        // -------------------------------------------------------------------------------------        
        $cyclismeJsarie = new Pratiquer();
		$cyclismeJsarie ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($cyclismeJsarie);
        $this->addReference('pratiquer-cylismeJsarie', $cyclismeJsarie);
        // -------------------------------------------------------------------------------------        
        /*$joggingJsarie = new Pratiquer();
		$joggingJsarie  ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($joggingJsarie);
        $this->addReference('pratiquer-joggingJsarie', $joggingJsarie);*/
        // -------------------------------------------------------------------------------------        
        $randonneJsarie = new Pratiquer();
		$randonneJsarie ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneJsarie);
        $this->addReference('pratiquer-randonneJsarie', $randonneJsarie);
        // -------------------------------------------------------------------------------------        
        $skiJsarie = new Pratiquer();
		$skiJsarie ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-confirme'))
		                ;
        $manager->persist($skiJsarie);
        $this->addReference('pratiquer-skiJsarie', $skiJsarie);
        // -------------------------------------------------------------------------------------        

        // Avauthey
        // -------------------------------------------------------------------------------------        
        $cyclismeAvauthey = new Pratiquer();
		$cyclismeAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($cyclismeAvauthey);
        $this->addReference('pratiquer-cylismeAvauthey', $cyclismeAvauthey);
        // -------------------------------------------------------------------------------------        
        $joggingAvauthey = new Pratiquer();
		$joggingAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($joggingAvauthey);
        $this->addReference('pratiquer-joggingAvauthey', $joggingAvauthey);
        // -------------------------------------------------------------------------------------        
        $randonneAvauthey = new Pratiquer();
		$randonneAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneAvauthey);
        $this->addReference('pratiquer-randonneAvauthey', $randonneAvauthey);
        // -------------------------------------------------------------------------------------        
        $skiAvauthey = new Pratiquer();
		$skiAvauthey ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($skiAvauthey);
        $this->addReference('pratiquer-skiAvauthey', $skiAvauthey);
        // -------------------------------------------------------------------------------------        

        // Jmpichon
        // -------------------------------------------------------------------------------------        
        $cyclismeJmpichon = new Pratiquer();
		$cyclismeJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($cyclismeJmpichon);
        $this->addReference('pratiquer-cylismeJmpichon', $cyclismeJmpichon);
        // -------------------------------------------------------------------------------------        
        $joggingJmpichon = new Pratiquer();
		$joggingJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($joggingJmpichon);
        $this->addReference('pratiquer-joggingJmpichon', $joggingJmpichon);
        // -------------------------------------------------------------------------------------        
        $randonneJmpichon = new Pratiquer();
		$randonneJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneJmpichon);
        $this->addReference('pratiquer-randonneJmpichon', $randonneJmpichon);
        // -------------------------------------------------------------------------------------        
        $skiJmpichon = new Pratiquer();
		$skiJmpichon ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($skiJmpichon);
        $this->addReference('pratiquer-skiJmpichon', $skiJmpichon);
        // -------------------------------------------------------------------------------------        

        // Fdartigues
        // -------------------------------------------------------------------------------------        
        $cyclismeFdartigues = new Pratiquer();
		$cyclismeFdartigues ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-cyclisme'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($cyclismeFdartigues);
        $this->addReference('pratiquer-cylismeFdartigues', $cyclismeFdartigues);
        // -------------------------------------------------------------------------------------        
        $joggingFdartigues = new Pratiquer();
		$joggingFdartigues ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-jogging'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($joggingFdartigues);
        $this->addReference('pratiquer-joggingFdartigues', $joggingFdartigues);
        // -------------------------------------------------------------------------------------        
       /* $randonneFdartigues = new Pratiquer();
		$randonneFdartigues ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-randonne'))
		                ->setNiveau($this->getReference('niveau-debutant'))
		                ;
        $manager->persist($randonneFdartigues);
        $this->addReference('pratiquer-randonneFdartigues', $randonneFdartigues);*/
        // -------------------------------------------------------------------------------------        
        $skiFdartigues = new Pratiquer();
		$skiFdartigues  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setSport($this->getReference('sport-ski'))
		                ->setNiveau($this->getReference('niveau-expert'))
		                ;
        $manager->persist($skiFdartigues);
        $this->addReference('pratiquer-skiFdartigues', $skiFdartigues);
        // -------------------------------------------------------------------------------------        

        
        
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
}
?>