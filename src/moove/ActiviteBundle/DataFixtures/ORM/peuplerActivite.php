<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use moove\ActiviteBundle\Entity\Activite;

class PeuplerActivite extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    protected function getTime($line)
    {
        if(substr_compare($line,"NOW", 0, 3, true) == 0)
		{
		    $date = new \DateTime('NOW');;
		    $argDuree = new \DateInterval('PT' . substr($line, 4, 2) . 'H' . substr($line, 7, 2) . 'M' . substr($line, 10, 2) . 'S');
		    if($line[3] == '+')
		        $date->add($argDuree);
		    else if ($line[3] == '-')
		        $date->sub($argDuree);
		    
		    return $date;
		}
		else
		{
            return new \DateTime($line);
		}
    }
    
    
    public function load(ObjectManager $manager)
    {
        $index = 1;
        $dateActuel = new \DateTime('NOW');

        $file = fopen(__DIR__ . "/peuplerActivite.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Activite();
            $temps  ->setDateHeureRDV($this->getTime($line[0]))
		            ->setDateFermeture($this->getTime($line[1]))
		            ->setDateCreation($dateActuel)
		            ->setDuree(new \DateTime($line[2]))
		            ->setNbPLaces(intval($line[3]))
		            ->setEstTerminee($line[4])
		            ->setOrganisateur($this->getReference('utilisateur-' . $line[5]))
		            ->setNiveauRequis($this->getReference('niveau-' . $line[6]))
		            ->setSportPratique($this->getReference('sport-' . $line[7]))
		            ->setLieuRDV($this->getReference('lieu-' . $line[8]))
		            ->setDescription($line[11])
		            ->setNbParticipants($line[12])

		            ;
		         
        
    		if($line[9] != "")
    		    $temps->setLieuDepart($this->getReference('lieu-' . $line[9]));
    		if($line[10] != "")
    		    $temps->setLieuArrivee($this->getReference('lieu-' . $line[10]));
    		  
            $manager->persist($temps);
            $this->addReference("activite-" . $index, $temps);
            $index++;
        }
        fclose($file);
        
       /* // -------------------------------------------------------------------------------------        
        $activite001 = new Activite();
		$activite001->setDateHeureRDV(new \DateTime('2016-02-23 8:00:00'))
		            ->setDuree(new \DateTime('8:30:00'))
		            ->setNbPLaces(4)
		            ->setDescription("Etape 24 du Tour de France 2015")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-02-20 8:00:00'))
		            ->setEstTerminee(false)
		            ->setOrganisateur($this->getReference('utilisateur-jmpichon'))
		            ->setNiveauRequis($this->getReference('niveau-intermediaire'))
		            ->setSportPratique($this->getReference('sport-cyclisme'))
		            ->setLieuRDV($this->getReference('lieu-001'))
		            ->setLieuDepart($this->getReference('lieu-002'))
		            ->setLieuArrivee($this->getReference('lieu-003'))
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
		            ->setLieuRDV($this->getReference('lieu-002'))
		            ->setLieuDepart($this->getReference('lieu-002'))
		            ->setLieuArrivee($this->getReference('lieu-004'))
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
		            ->setLieuRDV($this->getReference('lieu-006'))
		            //->setLieuDepart($this->getReference('lieu-003'))
		            //->setLieuArrivee($this->getReference('lieu-004'))
		            ;
        $manager->persist($activite003);
        $this->addReference('activite-003', $activite003);  
        // -------------------------------------------------------------------------------------        
        $activite004 = new Activite();
		$activite004->setDateHeureRDV(new \DateTime('2015-12-17 7:00:00'))
		            ->setDuree(new \DateTime('4:00:00'))
		            ->setNbPLaces(4)
		            ->setDescription("Ski Pro - La Mongie")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2015-12-16 6:00:00'))
		            ->setEstTerminee(true)
		            ->setOrganisateur($this->getReference('utilisateur-jsarie'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-ski'))
		            ->setLieuRDV($this->getReference('lieu-006'))
		            //->setLieuDepart($this->getReference('lieu-004'))
		            //->setLieuArrivee($this->getReference('lieu-001'))
		            ;
        $manager->persist($activite004);
        $this->addReference('activite-004', $activite004);  
        // -------------------------------------------------------------------------------------        
        $activite005 = new Activite();
		$activite005->setDateHeureRDV(new \DateTime('2015-12-10 7:00:00'))
		            ->setDuree(new \DateTime('4:00:00'))
		            ->setNbPLaces(4)
		            ->setDescription("Ski Pro - La Mongie")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2015-12-09 6:00:00'))
		            ->setEstTerminee(true)
		            ->setOrganisateur($this->getReference('utilisateur-jsarie'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-ski'))
		            ->setLieuRDV($this->getReference('lieu-006'))
		            //->setLieuDepart($this->getReference('lieu-004'))
		            //->setLieuArrivee($this->getReference('lieu-001'))
		            ;
        $manager->persist($activite005);
        $this->addReference('activite-005', $activite005);  
        // -------------------------------------------------------------------------------------        
        $activite006 = new Activite();
		$activite006->setDateHeureRDV(new \DateTime('2015-12-03 7:00:00'))
		            ->setDuree(new \DateTime('4:00:00'))
		            ->setNbPLaces(4)
		            ->setDescription("Ski Pro - La Mongie")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2015-12-02 6:00:00'))
		            ->setEstTerminee(true)
		            ->setOrganisateur($this->getReference('utilisateur-jsarie'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-ski'))
		            ->setLieuRDV($this->getReference('lieu-006'))
		            //->setLieuDepart($this->getReference('lieu-004'))
		            //->setLieuArrivee($this->getReference('lieu-001'))
		            ;
        $manager->persist($activite006);
        $this->addReference('activite-006', $activite006);  
        // -------------------------------------------------------------------------------------        
        $activite007 = new Activite();
		$activite007->setDateHeureRDV(new \DateTime('2016-12-02 7:00:00'))
		            ->setDuree(new \DateTime('4:00:00'))
		            ->setNbPLaces(4)
		            ->setDescription("Ski Pro - La Mongie")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-12-01 6:00:00'))
		            ->setEstTerminee(false)
		            ->setOrganisateur($this->getReference('utilisateur-jsarie'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-ski'))
		            ->setLieuRDV($this->getReference('lieu-006'))
		            //->setLieuDepart($this->getReference('lieu-004'))
		            //->setLieuArrivee($this->getReference('lieu-001'))
		            ;
        $manager->persist($activite007);
        $this->addReference('activite-007', $activite007);  
        
        // -------------------------------------------------------------------------------------        
        $activite008 = new Activite();
		$activite008->setDateHeureRDV(new \DateTime('2016-05-23 8:00:00'))
		            ->setDuree(new \DateTime('8:30:00'))
		            ->setNbPLaces(4)
		            ->setDescription("Etape 14 du Tour de France 2011")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-05-20 8:00:00'))
		            ->setEstTerminee(false)
		            ->setOrganisateur($this->getReference('utilisateur-jmpichon'))
		            ->setNiveauRequis($this->getReference('niveau-intermediaire'))
		            ->setSportPratique($this->getReference('sport-cyclisme'))
		            ->setLieuRDV($this->getReference('lieu-001'))
		            ->setLieuDepart($this->getReference('lieu-002'))
		            ->setLieuArrivee($this->getReference('lieu-003'))
		            ;
        $manager->persist($activite008);
        $this->addReference('activite-008', $activite008);
        // -------------------------------------------------------------------------------------        
        $activite009 = new Activite();
		$activite009->setDateHeureRDV(new \DateTime('2016-04-14 14:00:00'))
		            ->setDuree(new \DateTime('2:00:00'))
		            ->setNbPLaces(5)
		            ->setDescription("Entrainement sportif Haut niveau Jogging")
		            ->setDateCreation($dateActuel)
		            ->setDateFermeture(new \DateTime('2016-04-14 10:00:00'))
		            ->setEstTerminee(false)
		            ->setOrganisateur($this->getReference('utilisateur-avauthey'))
		            ->setNiveauRequis($this->getReference('niveau-expert'))
		            ->setSportPratique($this->getReference('sport-jogging'))
		            ->setLieuRDV($this->getReference('lieu-002'))
		            ->setLieuDepart($this->getReference('lieu-002'))
		            ->setLieuArrivee($this->getReference('lieu-004'))
		            ;
        $manager->persist($activite009);
        $this->addReference('activite-009', $activite009);
        // -------------------------------------------------------------------------------------   */     


        $manager->flush();
    }
    
    public function getOrder()
    {
        return 2;
    }
    
}
?>