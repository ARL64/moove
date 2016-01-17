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
		$cyclismeJsarie ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdSport($this->getReference('sport-cyclisme')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($cyclismeJsarie);
        $this->addReference('pratiquer-cylismeJsarie', $cyclismeJsarie);
        // -------------------------------------------------------------------------------------        
        $joggingJsarie = new Pratiquer();
		$joggingJsarie ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdSport($this->getReference('sport-jogging')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($joggingJsarie);
        $this->addReference('pratiquer-joggingJsarie', $joggingJsarie);
        // -------------------------------------------------------------------------------------        
        $randonneJsarie = new Pratiquer();
		$randonneJsarie ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdSport($this->getReference('sport-randonne')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($randonneJsarie);
        $this->addReference('pratiquer-randonneJsarie', $randonneJsarie);
        // -------------------------------------------------------------------------------------        
        $skiJsarie = new Pratiquer();
		$skiJsarie ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdSport($this->getReference('sport-ski')->getId())
		                ->setIdNiveau($this->getReference('niveau-confirme')->getId())
		                ;
        $manager->persist($skiJsarie);
        $this->addReference('pratiquer-skiJsarie', $skiJsarie);
        // -------------------------------------------------------------------------------------        

        // Avauthey
        // -------------------------------------------------------------------------------------        
        $cyclismeAvauthey = new Pratiquer();
		$cyclismeAvauthey ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdSport($this->getReference('sport-cyclisme')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($cyclismeAvauthey);
        $this->addReference('pratiquer-cylismeAvauthey', $cyclismeAvauthey);
        // -------------------------------------------------------------------------------------        
        $joggingAvauthey = new Pratiquer();
		$joggingAvauthey ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdSport($this->getReference('sport-jogging')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($joggingAvauthey);
        $this->addReference('pratiquer-joggingAvauthey', $joggingAvauthey);
        // -------------------------------------------------------------------------------------        
        $randonneAvauthey = new Pratiquer();
		$randonneAvauthey ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdSport($this->getReference('sport-randonne')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($randonneAvauthey);
        $this->addReference('pratiquer-randonneAvauthey', $randonneAvauthey);
        // -------------------------------------------------------------------------------------        
        $skiAvauthey = new Pratiquer();
		$skiAvauthey ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdSport($this->getReference('sport-ski')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($skiAvauthey);
        $this->addReference('pratiquer-skiAvauthey', $skiAvauthey);
        // -------------------------------------------------------------------------------------        

        // Jmpichon
        // -------------------------------------------------------------------------------------        
        $cyclismeJmpichon = new Pratiquer();
		$cyclismeJmpichon ->setIdUtilisateur($this->getReference('utilisateur-jmpichon')->getId())
		                ->setIdSport($this->getReference('sport-cyclisme')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($cyclismeJmpichon);
        $this->addReference('pratiquer-cylismeJmpichon', $cyclismeJmpichon);
        // -------------------------------------------------------------------------------------        
        $joggingJmpichon = new Pratiquer();
		$joggingJmpichon ->setIdUtilisateur($this->getReference('utilisateur-jmpichon')->getId())
		                ->setIdSport($this->getReference('sport-jogging')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($joggingJmpichon);
        $this->addReference('pratiquer-joggingJmpichon', $joggingJmpichon);
        // -------------------------------------------------------------------------------------        
        $randonneJmpichon = new Pratiquer();
		$randonneJmpichon ->setIdUtilisateur($this->getReference('utilisateur-jmpichon')->getId())
		                ->setIdSport($this->getReference('sport-randonne')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($randonneJmpichon);
        $this->addReference('pratiquer-randonneJmpichon', $randonneJmpichon);
        // -------------------------------------------------------------------------------------        
        $skiJmpichon = new Pratiquer();
		$skiJmpichon ->setIdUtilisateur($this->getReference('utilisateur-jmpichon')->getId())
		                ->setIdSport($this->getReference('sport-ski')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($skiJmpichon);
        $this->addReference('pratiquer-skiJmpichon', $skiJmpichon);
        // -------------------------------------------------------------------------------------        

        // Fdartigues
        // -------------------------------------------------------------------------------------        
        $cyclismeFdartigues = new Pratiquer();
		$cyclismeFdartigues ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdSport($this->getReference('sport-cyclisme')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($cyclismeFdartigues);
        $this->addReference('pratiquer-cylismeFdartigues', $cyclismeFdartigues);
        // -------------------------------------------------------------------------------------        
        $joggingFdartigues = new Pratiquer();
		$joggingFdartigues ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdSport($this->getReference('sport-jogging')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($joggingFdartigues);
        $this->addReference('pratiquer-joggingFdartigues', $joggingFdartigues);
        // -------------------------------------------------------------------------------------        
        $randonneFdartigues = new Pratiquer();
		$randonneFdartigues ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdSport($this->getReference('sport-randonne')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
		                ;
        $manager->persist($randonneFdartigues);
        $this->addReference('pratiquer-randonneFdartigues', $randonneFdartigues);
        // -------------------------------------------------------------------------------------        
        $skiFdartigues = new Pratiquer();
		$skiFdartigues ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdSport($this->getReference('sport-ski')->getId())
		                ->setIdNiveau($this->getReference('niveau-debutant')->getId())
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