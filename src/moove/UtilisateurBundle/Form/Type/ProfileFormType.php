<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace moove\UtilisateurBundle\Form\Type;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\File;

use moove\ActiviteBundle\Validator\Constraints\Adresse;

class ProfileFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'profile',
            // BC for SF < 2.8
            'intention'  => 'profile',
        ));
    }

    // BC for SF < 2.7
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    // BC for SF < 3.0
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * retourn app_user_profile
     * @return <i>string</i>
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array('label' => 'form.email','translation_domain' => 'FOSUserBundle'))
            ->add('nom', 'text', array('label' => 'form.nom', 'translation_domain' => 'FOSUserBundle'))
            ->add('prenom', 'text', array('label' => 'form.prenom', 'translation_domain' => 'FOSUserBundle'))
            ->add('dateNaissance', 'birthday', array('label' => 'form.birthday', 'translation_domain' => 'FOSUserBundle'))
            ->add('photo','file', array('label'=>'form.photo', 'translation_domain'=>'FOSUserBundle','required'=>false, 'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/bmp'
                        ],
                        'mimeTypesMessage' => 'Le type de l\'image est invalide',
                    ])
                ]
            ))
            ->add('sexe','choice',array('label' => 'form.sexe','choices'=>array('homme'=>'homme', 'femme'=>'femme'), 'translation_domain'=>'FOSUserBundle'))
            ->add('adresseLieuResidence','text',  array('required' => false, 'constraints' => new Adresse()))
        ;
    }
}
