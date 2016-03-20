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

class RegistrationFormType extends AbstractType
{
    private $class;

    /**
     * @param $class <i>String</i> The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Formulaire d'entité User
     * @param $builder <i>FormBuilderInterface<i/> 
     * @param $options <i>Array</i> 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //////// A OPTIMISER
        $builder
            // Prénom
            ->add('prenom', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
                'label' => 'form.prenom', 'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => array(
                    'placeholder' => 'Prénom',
                    'class' => 'form-control'
            )))
            // Nom
            ->add('nom', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextType'), array(
                'label' => 'form.nom', 'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => array(
                    'placeholder' => 'Nom',
                    'class' => 'form-control',
            )))
            // Email
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'), array(
                'label' => 'form.email', 'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => array(
                    'placeholder' => 'Adresse email',
                    'class' => 'form-control')))
            // Nom d'utilisateur
            ->add('username', null, array(
                'label' => 'form.username', 'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'sr-only'),
                'attr' => array(
                    'placeholder' => 'form.username',
                    'class' => 'form-control'
            )))
            // Mot de passe et confirmation du mot de passe
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password', 'label_attr' => array('class' => 'sr-only'),
                    'attr' => array(
                        'placeholder' => 'form.password',
                        'class' => 'form-control')),
                'second_options' => array('label' => 'form.password_confirmation', 'label_attr' => array('class' => 'sr-only'),
                    'attr' => array(
                        'placeholder' => 'form.password_confirmation',
                        'class' => 'form-control')),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            /* Date de naissance
            ->add('dateNaissance', 'birthday', array(
                'label' => 'form.birthday', 'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'sr-only'),
                    'attr' => array(
                        'placeholder' => 'form.birthday'),
                'input' => 'string',
                'widget' => 'single_text',
                'format' => 'YYYY-mm-dd'
            ))*/
            ->add('dateNaissance', 'date', [
                'translation_domain' => 'FOSUserBundle',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'placeholder' => 'form.birthday',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy'
                ]
            ])
            /* Calendrier founi
            ->add('dateNaissance', 'birthday', array(
                'label' => 'Date de naissance',
                'label_attr' => array('class' => 'sr-only'),
                'widget' => 'single_text'
            ))
            */
            ->add('sexe', 'choice', array(
                'choices' => array('homme' => ' Homme', 'femme' => ' Femme'),
                'multiple' => false,
                'expanded' => true
                
            ))
        ;
    }

    /**
     * 
     * @param <i>OptionsResolver</i> $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'registration',
            // BC for SF < 2.8
            'intention'  => 'registration',
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
     * retourn app_user_registration
     * @return <i>string</i>
     */
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}