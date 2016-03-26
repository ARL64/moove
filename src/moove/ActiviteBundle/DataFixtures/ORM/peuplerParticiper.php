<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use moove\ActiviteBundle\Entity\Participer;
use moove\ActiviteBundle\Entity\Utilisateur;
use moove\ActiviteBundle\Entity\Activite;

class PeuplerParticiper extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $i = 1;
        $isFinish = false;
        while(!$isFinish)
        {
            try
            {
                $this->getReference('activite-' . $i);
            }
            catch(Exception $e)
            {
                $isFinish = true;
            }
            
            if(!$isFinish)
            {
                $temps = new Participer();
	           	$temps->setUtilisateur($this->getReference('activite-' . $i)->getOrganisateur())
		                ->setActivite($this->getReference('activite-' . $i))
		                ->setEstAccepte(1)
		                ;
                $manager->persist($temps);
                $this->addReference('organisateur-' . $i, $temps);  
            }
            $i++;
            
            // le try catch est sencé vérifier justement cette condition, mais sa ne veux pas...
            // ducoup, pour le moment incrémenté juste cette variable du nombre d'activité + 1...
            if($i == (11 + 1))
                break;
        }
        
        $index = 1;
        $file = fopen(__DIR__ . "/peuplerParticiper.csv", "r");

        while(true)
        {
            $index++;
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Participer();
            $temps  ->setUtilisateur($this->getReference('utilisateur-'.$line[0]))
                    ->setActivite($this->getReference('activite-' . $line[1]))
		            ->setEstAccepte($line[2])
		            ;

		            
            $manager->persist($temps);
            $this->addReference("participer-" . $index++, $temps);
            
        }
        fclose($file);
        
/*        // Voir à automatiser cette génération dans les classes d'entité
        // -------------------------------------------------------------------------------------        
        $organisateur001 = new Participer();
		$organisateur001->setUtilisateur($this->getReference('activite-001')->getOrganisateur())
		                ->setActivite($this->getReference('activite-001'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur001);
        $this->addReference('organisateur-001', $organisateur001);
        // -------------------------------------------------------------------------------------        
        $organisateur002 = new Participer();
		$organisateur002->setUtilisateur($this->getReference('activite-002')->getOrganisateur())
		                ->setActivite($this->getReference('activite-002'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur002);
        $this->addReference('organisateur-002', $organisateur002);
        // -------------------------------------------------------------------------------------        
        $organisateur003 = new Participer();
		$organisateur003->setUtilisateur($this->getReference('activite-003')->getOrganisateur())
		                ->setActivite($this->getReference('activite-003'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur003);
        $this->addReference('organisateur-003', $organisateur003);
        // -------------------------------------------------------------------------------------        
        $organisateur004 = new Participer();
		$organisateur004->setUtilisateur($this->getReference('activite-004')->getOrganisateur())
		                ->setActivite($this->getReference('activite-004'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur004);
        $this->addReference('organisateur-004', $organisateur004);
        // -------------------------------------------------------------------------------------        
        $organisateur005 = new Participer();
		$organisateur005->setUtilisateur($this->getReference('activite-005')->getOrganisateur())
		                ->setActivite($this->getReference('activite-005'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur005);
        $this->addReference('organisateur-005', $organisateur005);
        // -------------------------------------------------------------------------------------        
        $organisateur006 = new Participer();
		$organisateur006->setUtilisateur($this->getReference('activite-006')->getOrganisateur())
		                ->setActivite($this->getReference('activite-006'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur006);
        $this->addReference('organisateur-006', $organisateur006);
        // -------------------------------------------------------------------------------------        
        $organisateur007 = new Participer();
		$organisateur007->setUtilisateur($this->getReference('activite-007')->getOrganisateur())
		                ->setActivite($this->getReference('activite-007'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur007);
        $this->addReference('organisateur-007', $organisateur007);
        // -------------------------------------------------------------------------------------        
        $organisateur008 = new Participer();
		$organisateur008->setUtilisateur($this->getReference('activite-008')->getOrganisateur())
		                ->setActivite($this->getReference('activite-008'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur008);
        $this->addReference('organisateur-008', $organisateur008);
        // -------------------------------------------------------------------------------------        
        $organisateur009 = new Participer();
		$organisateur009->setUtilisateur($this->getReference('activite-009')->getOrganisateur())
		                ->setActivite($this->getReference('activite-009'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($organisateur009);
        $this->addReference('organisateur-009', $organisateur009);
        // -------------------------------------------------------------------------------------        
*/

/*        
        // -------------------------------------------------------------------------------------        
        $participer001 = new Participer();
		$participer001  ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setActivite($this->getReference('activite-001'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer001);
        $this->addReference('participer-001', $participer001);       
        // -------------------------------------------------------------------------------------        
        $participer002 = new Participer();
		$participer002  ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setActivite($this->getReference('activite-001'))
		                ->setEstAccepte(2)
		                ;
        $manager->persist($participer002);
        $this->addReference('participer-002', $participer002);
        // -------------------------------------------------------------------------------------       
        $participer003 = new Participer();
		$participer003  ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setActivite($this->getReference('activite-002'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer003);
        $this->addReference('participer-003', $participer003);
        // -------------------------------------------------------------------------------------       
        $participer004 = new Participer();
		$participer004  ->setUtilisateur($this->getReference('utilisateur-jmpichon'))
		                ->setActivite($this->getReference('activite-002'))
		                ->setEstAccepte(0)
		                ;
        $manager->persist($participer004);
        $this->addReference('participer-004', $participer004);
        // -------------------------------------------------------------------------------------       
        $participer005 = new Participer();
		$participer005  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setActivite($this->getReference('activite-001'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer005);
        $this->addReference('participer-005', $participer005);
        // -------------------------------------------------------------------------------------   
        
        $participer006 = new Participer();
		$participer006  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setActivite($this->getReference('activite-003'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer006);
        $this->addReference('participer-006', $participer006);
        // -------------------------------------------------------------------------------------       
        $participer007 = new Participer();
		$participer007  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setActivite($this->getReference('activite-004'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer007);
        $this->addReference('participer-007', $participer007);
        // -------------------------------------------------------------------------------------       
        $participer008 = new Participer();
		$participer008  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setActivite($this->getReference('activite-005'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer008);
        $this->addReference('participer-008', $participer008);
        // -------------------------------------------------------------------------------------       
        $participer009 = new Participer();
		$participer009  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setActivite($this->getReference('activite-006'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer009);
        $this->addReference('participer-009', $participer009);
        // -------------------------------------------------------------------------------------       
        $participer010 = new Participer();
		$participer010  ->setUtilisateur($this->getReference('utilisateur-fdartigues'))
		                ->setActivite($this->getReference('activite-007'))
		                ->setEstAccepte(0)
		                ;
        $manager->persist($participer010);
        $this->addReference('participer-010', $participer010);
        // -------------------------------------------------------------------------------------       
        $participer011 = new Participer();
		$participer011  ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setActivite($this->getReference('activite-005'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer011);
        $this->addReference('participer-011', $participer011);
        // -------------------------------------------------------------------------------------       
        $participer012 = new Participer();
		$participer012  ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setActivite($this->getReference('activite-006'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer012);
        $this->addReference('participer-012', $participer012);
        // -------------------------------------------------------------------------------------       
        $participer013 = new Participer();
		$participer013  ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setActivite($this->getReference('activite-007'))
		                ->setEstAccepte(1)
		                ;
        $manager->persist($participer013);
        $this->addReference('participer-013', $participer013);
        // -------------------------------------------------------------------------------------       
        $participer014 = new Participer();
		$participer014  ->setUtilisateur($this->getReference('utilisateur-avauthey'))
		                ->setActivite($this->getReference('activite-008'))
		                ->setEstAccepte(0)
		                ;
        $manager->persist($participer014);
        $this->addReference('participer-014', $participer014);
        // -------------------------------------------------------------------------------------       
        $participer015 = new Participer();
		$participer015  ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setActivite($this->getReference('activite-008'))
		                ->setEstAccepte(0)
		                ;
        $manager->persist($participer015);
        $this->addReference('participer-015', $participer015);
        // -------------------------------------------------------------------------------------       
        $participer016 = new Participer();
		$participer016  ->setUtilisateur($this->getReference('utilisateur-jsarie'))
		                ->setActivite($this->getReference('activite-009'))
		                ->setEstAccepte(0)
		                ;
        $manager->persist($participer016);
        $this->addReference('participer-016', $participer016);
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