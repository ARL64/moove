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
        $index = 1;
        $file = fopen(__DIR__ . "/peuplerLieu.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Lieu();
            $temps  ->setNom($line[0])
                    ->setNumeroRue(intval($line[1]))
                    ->setNomRue($line[2])
                    ->setComplementAdresse($line[3])
                    ->setCodePostal(intval($line[4]))
                    ->setVille($line[5])
                    ->setLatitude(floatval($line[6]."The"))
                    ->setLongitude(floatval($line[7]."The"))
                    ;
		            
            $manager->persist($temps);
            $this->addReference("lieu-" . $index, $temps);
            $index++;
        }
        fclose($file);

        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1; // avant User /!\
    }
}
?>