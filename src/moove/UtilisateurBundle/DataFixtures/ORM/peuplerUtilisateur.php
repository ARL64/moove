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


class PeuplerUtilisatueur extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        
        // -------------------------------------------------------------------------------------        
        $jodge = $userManager->createUser();
        $jodge  ->setNom("Sarie")
                ->setPrenom("Joey")
                //->setURLAvatar("A_CHANGER")
                ->setDateNaissance(new \DateTime('1996-03-02'))
                ->setUsername("Jodge")
                ->setEmail("joey.sarie@gmail.com")
                ->setEnabled(true)
                ->setPlainPassword('azerty') 
                //->setRoles(new array())
                ->setLieuResidence($this->getReference('lieu-001'));
                ;
        $userManager->updateUser($jodge, true);
        $this->addReference('utilisateur-jsarie', $jodge);
        // -------------------------------------------------------------------------------------        
        $avauthey = $userManager->createUser();
        $avauthey   ->setNom("Vauthey")
                    ->setPrenom("Antoine")
                    //->setURLAvatar("A_CHANGER")
                    ->setDateNaissance(new \DateTime('1996-09-22'))
                    ->setUsername("avauthey")
                    ->setEmail("vauthey.antoine@gmail.com")
                    ->setEnabled(true)
                    ->setPlainPassword('qsdfgh') 
                    //->setRoles(new array())
                    ->setLieuResidence($this->getReference('lieu-002'));
                    ;
        $userManager->updateUser($avauthey, true);
        $this->addReference('utilisateur-avauthey', $avauthey);
        // -------------------------------------------------------------------------------------        
        $jmpichon = $userManager->createUser();
        $jmpichon   ->setNom("Pichon")
                    ->setPrenom("Jean-Maxime")
                    //->setURLAvatar("A_CHANGER")
                    ->setDateNaissance(new \DateTime('1996-06-03'))
                    ->setUsername("Reigarth")
                    ->setEmail("rtw@live.fr")
                    ->setEnabled(true)
                    ->setPlainPassword('anepasretenir') 
                     //->setRoles(new array())
                    ->setLieuResidence($this->getReference('lieu-003'));
                    ;
        $userManager->updateUser($jmpichon, true);
        $this->addReference('utilisateur-jmpichon', $jmpichon);
        // -------------------------------------------------------------------------------------        
        $fdartigues = $userManager->createUser();
        $fdartigues ->setNom("Dartigues")
                    ->setPrenom("Fabien")
                    //->setURLAvatar("A_CHANGER")
                    ->setDateNaissance(new \DateTime('1996-05-29'))
                    ->setUsername("arl")
                    ->setEmail("fabien.dartigues@outlook.fr")
                    ->setEnabled(true)
                    ->setPlainPassword('test') 
                    //->setRoles(new array())
                    ->setLieuResidence($this->getReference('lieu-004'));
                    ;
        $userManager->updateUser($fdartigues, true);
        $this->addReference('utilisateur-fdartigues', $fdartigues);
        // -------------------------------------------------------------------------------------        
    }
    
    public function getOrder()
    {
        return 2;
    }
}

?>