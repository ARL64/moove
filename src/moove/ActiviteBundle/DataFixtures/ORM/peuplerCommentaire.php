<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use moove\ActiviteBundle\Entity\Commentaire;

class PeuplerCommentaire extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
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

        $file = fopen(__DIR__ . "/peuplerCommentaire.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = new Commentaire();
            $temps  ->setContenu($line[0])
		            ->setPosteA($this->getTime($line[1]))
		            ->setType("utilisateurs")
		            ->setActivite($this->getReference('activite-' . $line[2]))
		            ->setUtilisateur($this->getReference('utilisateur-' . $line[3]))
		            ;
		         
            $manager->persist($temps);
            $this->addReference("commentaires-" . $index, $temps);
            $index++;
        }
        fclose($file);
      


        $manager->flush();
    }
    
    public function getOrder()
    {
        return 3;
    }
    
}
?>