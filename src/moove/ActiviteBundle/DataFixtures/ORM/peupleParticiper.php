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
        // Voir à automatiser cette génération dans les classes d'entité
        // -------------------------------------------------------------------------------------        
        $organisateur001 = new Participer();
		$organisateur001->setIdUtilisateur($this->getReference('activite-001')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-001')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur001);
        $this->addReference('organisateur-001', $organisateur001);
        // -------------------------------------------------------------------------------------        
        $organisateur002 = new Participer();
		$organisateur002->setIdUtilisateur($this->getReference('activite-002')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-002')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur002);
        $this->addReference('organisateur-002', $organisateur002);
        // -------------------------------------------------------------------------------------        
        $organisateur003 = new Participer();
		$organisateur003->setIdUtilisateur($this->getReference('activite-003')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-003')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur003);
        $this->addReference('organisateur-003', $organisateur003);
        // -------------------------------------------------------------------------------------        


        
        // -------------------------------------------------------------------------------------        
        $participer001 = new Participer();
		$participer001  ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdActivite($this->getReference('activite-001')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer001);
        $this->addReference('participer-001', $participer001);       
        // -------------------------------------------------------------------------------------        
        $participer002 = new Participer();
		$participer002  ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdActivite($this->getReference('activite-001')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer002);
        $this->addReference('participer-002', $participer002);
        // -------------------------------------------------------------------------------------       
        $participer003 = new Participer();
		$participer003  ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdActivite($this->getReference('activite-002')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer003);
        $this->addReference('participer-003', $participer003);
        // -------------------------------------------------------------------------------------       
        $participer004 = new Participer();
		$participer004  ->setIdUtilisateur($this->getReference('utilisateur-jmpichon')->getId())
		                ->setIdActivite($this->getReference('activite-002')->getId())
		                ->setEstAccepte(false)
		                ;
        $manager->persist($participer004);
        $this->addReference('participer-004', $participer004);
        // -------------------------------------------------------------------------------------       
        $participer005 = new Participer();
		$participer005  ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdActivite($this->getReference('activite-001')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer005);
        $this->addReference('participer-005', $participer005);
        // -------------------------------------------------------------------------------------   
        
        $participer006 = new Participer();
		$participer006  ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdActivite($this->getReference('activite-003')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer006);
        $this->addReference('participer-006', $participer006);
        // -------------------------------------------------------------------------------------       
        $participer007 = new Participer();
		$participer007  ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdActivite($this->getReference('activite-004')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer007);
        $this->addReference('participer-007', $participer007);
        // -------------------------------------------------------------------------------------       

  
 
        
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
}
?>