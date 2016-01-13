<?php
namespace moove\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use moove\ActiviteBundle\Entity\Utilisateur;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

use FOS\UserBundle\Doctrine\UserManager;


class PeuplerUtilisatueur extends ContainerAware implements FixtureInterface
{
    //public $jodge;

    public function load(ObjectManager $manager)
    {

        $userManager = $this->container->get('fos_user.user_manager');
        
        $jodge = $userManager->createUser();
        $jodge  ->setNom("Joey")
                ->setPrenom("Sarie")
                //->setURLAvatar("A_CHANGER")
                ->setDateNaissance(new \DateTime('1996-03-02'))
                ->setUsername("Jodge")
                ->setEmail("joey.sarie@gmail.com")
                ->setEnabled(true)
                ->setPlainPassword('azerty') 
                //->setRoles(new array())
                ;
        $userManager->updateUser($jodge, true);
        
        $avauthey = $userManager->createUser();
        $avauthey  ->setNom("Vauthey")
                ->setPrenom("Antoine")
                //->setURLAvatar("A_CHANGER")
                ->setDateNaissance(new \DateTime('1996-09-22'))
                ->setUsername("avauthey")
                ->setEmail("vauthey.antoine@gmail.com")
                ->setEnabled(true)
                ->setPlainPassword('qsdfgh') 
                //->setRoles(new array())
                ;
        $userManager->updateUser($avauthey, true);

        $jmpichon = $userManager->createUser();
        $jmpichon  ->setNom("Pichon")
                ->setPrenom("Jean-Maxime")
                //->setURLAvatar("A_CHANGER")
                ->setDateNaissance(new \DateTime('1996-06-03'))
                ->setUsername("Reigarth")
                ->setEmail("rtw@live.fr")
                ->setEnabled(true)
                ->setPlainPassword('anepasretenir') 
                //->setRoles(new array())
                ;
        $userManager->updateUser($jmpichon, true);

        $fdartigues = $userManager->createUser();
        $fdartigues  ->setNom("Dartigues")
                ->setPrenom("Fabien")
                //->setURLAvatar("A_CHANGER")
                ->setDateNaissance(new \DateTime('1996-05-29'))
                ->setUsername("arl")
                ->setEmail("fabien.dartigues@outlook.fr")
                ->setEnabled(true)
                ->setPlainPassword('test') 
                //->setRoles(new array())
                ;
        $userManager->updateUser($fdartigues, true);

    }
}

?>