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
        $organisateur004 = new Participer();
		$organisateur004->setIdUtilisateur($this->getReference('activite-004')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-004')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur004);
        $this->addReference('organisateur-004', $organisateur004);
        // -------------------------------------------------------------------------------------        
        $organisateur005 = new Participer();
		$organisateur005->setIdUtilisateur($this->getReference('activite-005')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-005')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur005);
        $this->addReference('organisateur-005', $organisateur005);
        // -------------------------------------------------------------------------------------        
        $organisateur006 = new Participer();
		$organisateur006->setIdUtilisateur($this->getReference('activite-006')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-006')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur006);
        $this->addReference('organisateur-006', $organisateur006);
        // -------------------------------------------------------------------------------------        
        $organisateur007 = new Participer();
		$organisateur007->setIdUtilisateur($this->getReference('activite-007')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-007')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur007);
        $this->addReference('organisateur-007', $organisateur007);
        // -------------------------------------------------------------------------------------        
        $organisateur008 = new Participer();
		$organisateur008->setIdUtilisateur($this->getReference('activite-008')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-008')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur008);
        $this->addReference('organisateur-008', $organisateur008);
        // -------------------------------------------------------------------------------------        
        $organisateur009 = new Participer();
		$organisateur009->setIdUtilisateur($this->getReference('activite-009')->getOrganisateur()->getId())
		                ->setIdActivite($this->getReference('activite-009')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($organisateur009);
        $this->addReference('organisateur-009', $organisateur009);
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
		                ->setEstAccepte(false)
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
        $participer008 = new Participer();
		$participer008  ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdActivite($this->getReference('activite-005')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer008);
        $this->addReference('participer-008', $participer008);
        // -------------------------------------------------------------------------------------       
        $participer009 = new Participer();
		$participer009  ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdActivite($this->getReference('activite-006')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer009);
        $this->addReference('participer-009', $participer009);
        // -------------------------------------------------------------------------------------       
        $participer010 = new Participer();
		$participer010  ->setIdUtilisateur($this->getReference('utilisateur-fdartigues')->getId())
		                ->setIdActivite($this->getReference('activite-007')->getId())
		                ->setEstAccepte(false)
		                ;
        $manager->persist($participer010);
        $this->addReference('participer-010', $participer010);
        // -------------------------------------------------------------------------------------       
        $participer011 = new Participer();
		$participer011  ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdActivite($this->getReference('activite-005')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer011);
        $this->addReference('participer-011', $participer011);
        // -------------------------------------------------------------------------------------       
        $participer012 = new Participer();
		$participer012  ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdActivite($this->getReference('activite-006')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer012);
        $this->addReference('participer-012', $participer012);
        // -------------------------------------------------------------------------------------       
        $participer013 = new Participer();
		$participer013  ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdActivite($this->getReference('activite-007')->getId())
		                ->setEstAccepte(true)
		                ;
        $manager->persist($participer013);
        $this->addReference('participer-013', $participer013);
        // -------------------------------------------------------------------------------------       
        $participer014 = new Participer();
		$participer014  ->setIdUtilisateur($this->getReference('utilisateur-avauthey')->getId())
		                ->setIdActivite($this->getReference('activite-008')->getId())
		                ->setEstAccepte(false)
		                ;
        $manager->persist($participer014);
        $this->addReference('participer-014', $participer014);
        // -------------------------------------------------------------------------------------       
        $participer015 = new Participer();
		$participer015  ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdActivite($this->getReference('activite-008')->getId())
		                ->setEstAccepte(false)
		                ;
        $manager->persist($participer015);
        $this->addReference('participer-015', $participer015);
        // -------------------------------------------------------------------------------------       
        $participer016 = new Participer();
		$participer016  ->setIdUtilisateur($this->getReference('utilisateur-jsarie')->getId())
		                ->setIdActivite($this->getReference('activite-009')->getId())
		                ->setEstAccepte(false)
		                ;
        $manager->persist($participer016);
        $this->addReference('participer-016', $participer016);
        // -------------------------------------------------------------------------------------       

        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
}
?>