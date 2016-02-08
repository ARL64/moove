<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Lieu;

class PeuplerLieu extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
         $file = fopen(__DIR__ . "/peuplerLieu.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Lieu();
            $temps  ->setNom($line[1])
                    ->setNumeroRue(intval($line[2]))
                    ->setNomRue($line[3])
                    ->setComplementAdresse($line[4])
                    ->setCodePostal(intval($line[5]))
                    ->setVille($line[6])
                    ->setLatitude(floatval($line[7]."The"))
                    ->setLongitude(floatval($line[8]."The"))
                    ;
		            
            $manager->persist($temps);
            $this->addReference("lieu-" . $line[0], $temps);
            
        }
        fclose($file);
        
/*        
        // -------------------------------------------------------------------------------------
        $lieu001 = new Lieu();
        $lieu001->setNom("cité U Pierre Bidart")
                ->setNumeroRue(55)
                ->setNomRue("Avenue Mirambeau")
                ->setComplementAdresse("C27")
                ->setCodePostal(64600)
                ->setVille("Anglet")
                ->setLatitude(43.4834111)
                ->setLongitude(-1.5111817);
        $manager->persist($lieu001);
        $this->addReference('lieu-001', $lieu001);
        // -------------------------------------------------------------------------------------
        $lieu002 = new Lieu();
        $lieu002->setNom("cité U Pierre Bidart")
                ->setNumeroRue(55)
                ->setNomRue("Avenue Mirambeau")
                ->setComplementAdresse("A1X") // pour tester 
                ->setCodePostal(64600)
                ->setVille("Anglet")
                ->setLatitude(43.4834111)
                ->setLongitude(-1.5111817);
        $manager->persist($lieu002);
        $this->addReference('lieu-002', $lieu002);
        // -------------------------------------------------------------------------------------
        $lieu003 = new Lieu();
        $lieu003->setNom("Iut Anglet")
                ->setNumeroRue(0)
                ->setNomRue("Allée du Parc Montaury")
                ->setComplementAdresse("")
                ->setCodePostal(64600)
                ->setVille("Anglet")
                ->setLatitude(43.4789782)
                ->setLongitude(-1.5104336);
        $manager->persist($lieu003);
        $this->addReference('lieu-003', $lieu003);
        // -------------------------------------------------------------------------------------
        $lieu004 = new Lieu();
        $lieu004->setNom("Petit Bayonne")
                ->setNumeroRue(0)
                ->setNomRue("")
                ->setComplementAdresse("")
                ->setCodePostal(64600)
                ->setVille("Bayonne")
                ->setLatitude(43.4904125) 
                ->setLongitude(-1.4748966);
        $manager->persist($lieu004);
        $this->addReference('lieu-004', $lieu004);
        // -------------------------------------------------------------------------------------
        $lieu005 = new Lieu();
        $lieu005->setNom("Gare Tarbe")
                ->setNumeroRue(25)
                ->setNomRue("Avenue Maréchal Joffre")
                ->setComplementAdresse("")
                ->setCodePostal(65200)
                ->setVille("Tarbes")
                ->setLatitude(43.2399829) 
                ->setLongitude(0.0672133);
        $manager->persist($lieu005);
        $this->addReference('lieu-005', $lieu005);
        // -------------------------------------------------------------------------------------
        $lieu006 = new Lieu();
        $lieu006->setNom("Régie Intercommunale du Tourmalet")
                ->setNumeroRue(0)
                ->setNomRue("Boulevard du Pic du Midi")
                ->setComplementAdresse("")
                ->setCodePostal(65200)
                ->setVille("La Mongie")
                ->setLatitude(42.9103003) 
                ->setLongitude(0.1786542);
        $manager->persist($lieu006);
        $this->addReference('lieu-006', $lieu006);
        // -------------------------------------------------------------------------------------
*/


        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1; // avant User /!\
    }
}
?>