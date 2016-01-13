<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use moove\ActiviteBundle\Entity\Niveau;

class PeuplerNiveau implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $debutant = new Niveau();
        $debutant->setLibelle("Débutant");
        $manager->persist($debutant);
        

        $intermediaire = new Niveau();
        $intermediaire->setLibelle("Intermédiaire");
        $manager->persist($intermediaire);
        
        $confirme = new Niveau();
        $confirme->setLibelle("Confirmé");
        $manager->persist($confirme);
        
        $expert = new Niveau();
        $expert->setLibelle("Expert");
        $manager->persist($expert);
        
        $manager->flush();
    }
}
?>