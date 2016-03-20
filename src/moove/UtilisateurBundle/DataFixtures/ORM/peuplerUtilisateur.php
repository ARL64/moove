<?php
namespace moove\UtilisateurBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use moove\UtilisateurBundle\Entity\Utilisateur;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use FOS\UserBundle\Doctrine\UserManager;


class PeuplerUtilisateur extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
         $userManager = $this->container->get('fos_user.user_manager');

         $file = fopen(__DIR__ . "/peuplerUtilisateur.csv", "r");

        while(true)
        {
            $line = fgetcsv($file, 0, ';');
            if(empty($line) || is_null($line))
                break;
            $temps = $userManager->createUser();
            $temps  ->setNom($line[1])
                    ->setPrenom($line[2])
                    ->setURLAvatar($line[3])
                    ->setDateNaissance(new \DateTime($line[4]))
                    ->setUsername($line[5])
                    ->setEmail($line[6])
                    ->setEnabled(boolval($line[7]))
                    ->setPlainPassword($line[8])
                    ->setSexe($line[9])
                    ->setLieuResidence($this->getReference('lieu-'.$line[10]))
                    ;
		            
		    if(strcmp($line[11], "yes") == 0)
		    {
		        $temps->addRole('ROLE_ADMIN');
		        $temps->addRole('ROLE_SONATA_ADMIN');
		    }
            $userManager->updateUser($temps, true);
            $this->addReference("utilisateur-" . $line[0], $temps);
            
        }
        fclose($file);

        
/*        // -------------------------------------------------------------------------------------        
        $jodge = $userManager->createUser();
        $jodge  ->setNom("Sarie")
                ->setPrenom("Joey")
                ->setURLAvatar("user1.png")
                ->setDateNaissance(new \DateTime('1996-03-02'))
                ->setUsername("Jodge")
                ->setEmail("joey.sarie@gmail.com")
                ->setEnabled(true)
                ->setPlainPassword('azerty')
                ->setSexe('homme')
                //->setRoles(new array())
                ->setLieuResidence($this->getReference('lieu-001'))
                //->addPratiquer($this->getReference('pratiquer-cylismeJsarie'))
                //->addPratiquer($this->getReference('pratiquer-joggingJsarie'))
                //->addPratiquer($this->getReference('pratiquer-randonneJsarie'))
                //->addPratiquer($this->getReference('pratiquer-skiJsarie'))
                ;
        $userManager->updateUser($jodge, true);
        $this->addReference('utilisateur-jsarie', $jodge);
        // -------------------------------------------------------------------------------------        
        $avauthey = $userManager->createUser();
        $avauthey   ->setNom("Vauthey")
                    ->setPrenom("Antoine")
                    ->setURLAvatar("user2.jpg")
                    ->setDateNaissance(new \DateTime('1996-09-22'))
                    ->setUsername("avauthey")
                    ->setEmail("vauthey.antoine@gmail.com")
                    ->setEnabled(true)
                    ->setPlainPassword('cpassa')
                    ->setSexe('homme')
                    //->setRoles(new array())
                    ->setLieuResidence($this->getReference('lieu-002'))
                    //->addPratiquer($this->getReference('pratiquer-cylismeAvauthey'))
                    //->addPratiquer($this->getReference('pratiquer-joggingAvauthey'))
                    //->addPratiquer($this->getReference('pratiquer-randonneAvauthey'))
                    //->addPratiquer($this->getReference('pratiquer-skiAvauthey'))
                    ;
        $userManager->updateUser($avauthey, true);
        $this->addReference('utilisateur-avauthey', $avauthey);
        // -------------------------------------------------------------------------------------        
        $jmpichon = $userManager->createUser();
        $jmpichon   ->setNom("Pichon")
                    ->setPrenom("Jean-Maxime")
                    ->setURLAvatar("user3.jpg")
                    ->setDateNaissance(new \DateTime('1996-06-03'))
                    ->setUsername("Reigarth")
                    ->setEmail("rtw@live.fr")
                    ->setEnabled(true)
                    ->setPlainPassword('anepasretenir')
                    ->setSexe('homme')
                     //->setRoles(new array())
                    ->setLieuResidence($this->getReference('lieu-003'))
                    //->addPratiquer($this->getReference('pratiquer-cylismeJmpichon'))
                    //->addPratiquer($this->getReference('pratiquer-joggingJmpichon'))
                    //->addPratiquer($this->getReference('pratiquer-randonneJmpichon'))
                    //->addPratiquer($this->getReference('pratiquer-skiJmpichon'))
                    ;
        $userManager->updateUser($jmpichon, true);
        $this->addReference('utilisateur-jmpichon', $jmpichon);
        // -------------------------------------------------------------------------------------        
        $fdartigues = $userManager->createUser();
        $fdartigues ->setNom("Dartigues")
                    ->setPrenom("Fabien")
                    ->setURLAvatar("user4.jpg")
                    ->setDateNaissance(new \DateTime('1996-05-29'))
                    ->setUsername("arl")
                    ->setEmail("fabien.dartigues@outlook.fr")
                    ->setEnabled(true)
                    ->setPlainPassword('test')
                    ->setSexe('homme')
                    //->setRoles(new array())
                    ->setLieuResidence($this->getReference('lieu-004'))
                    //->addPratiquer($this->getReference('pratiquer-cylismeFdartigues'))
                    //->addPratiquer($this->getReference('pratiquer-joggingFdartigues'))
                    //->addPratiquer($this->getReference('pratiquer-randonneFdartigues'))
                    //->addPratiquer($this->getReference('pratiquer-skiFdartigues'))
                    ;
        $userManager->updateUser($fdartigues, true);
        $this->addReference('utilisateur-fdartigues', $fdartigues);
*/        // -------------------------------------------------------------------------------------        
    }
    
    public function getOrder()
    {
        return 2;
    }
}

?>