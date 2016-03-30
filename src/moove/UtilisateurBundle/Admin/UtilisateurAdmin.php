<?php

namespace moove\UtilisateurBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UtilisateurAdmin extends Admin
{
    /**
     * @param $datagridMapper <i>DatagridMapper</i>
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('sexe')
            ->add('dateNaissance')
            ->add('enabled')
            ->add('lastLogin')
            ->add('roles')
        ;
    }

    /**
     * @param <i>$listMapper</i> ListMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('username', 'text', array('label' => 'Nom d\'utilisateur'))
            ->add('prenom', 'text', array('label' => 'Prénom'))
            ->add('nom')
            ->add('email', 'text', array('label' => 'Adresse email'))
            ->add('sexe', 'text', array('label' => 'Genre'))
            ->add('dateNaissance', 'date', array('label' => 'Date de naissance', 'format' => 'd/m/y'))
            ->add('enabled')
            ->add('lastLogin', 'date', array('label' => 'Dernière connexion'))
            ->add('roles')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param $formMapper <i>FormMapper</i>
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username', 'text', array('label' => 'Nom d\'utilisateur'))
            ->add('prenom', 'text', array('label' => 'Prénom'))
            ->add('nom')
            ->add('email', 'text', array('label' => 'Adresse email'))
            ->add('sexe', 'text', array('label' => 'Genre'))
            ->add('dateNaissance', 'date', array('label' => 'Date de naissance', 'format' => 'd/M/y'))
            ->add('roles')
        ;
    }

    /**
     * @param $showMapper <i>ShowMapper</i>
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('username', null, array('label' => 'Nom d\'utilisateur'))
            ->add('nom')
            ->add('prenom', null, array('label' => 'Prénom'))
            ->add('email')
            ->add('URLAvatar', null, array('label' => 'URL de l\'avatar'))
            ->add('sexe', null, array('label' => 'Genre'))
            ->add('dateNaissance', 'date', array('label' => 'Date de naissance', 'format' => 'd/m/y'))
            ->add('enabled')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')

        ;
    }
}
