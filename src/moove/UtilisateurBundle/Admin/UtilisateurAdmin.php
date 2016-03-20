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
            ->add('id')
            ->add('username')
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('sexe')
            ->add('dateNaissance')
            ->add('enabled')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('passwordRequestedAt')
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
            ->add('username')
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('sexe')
            ->add('dateNaissance')
            ->add('enabled')
            ->add('lastLogin')
            ->add('passwordRequestedAt')
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
            ->add('username')
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('sexe')
            ->add('dateNaissance')
            ->add('enabled')
            ->add('roles')
        ;
    }

    /**
     * @param $showMapper <i>ShowMapper</i>
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('id')
            ->add('nom')
            ->add('prenom')
            ->add('URLAvatar')
            ->add('sexe')
            ->add('dateNaissance')
        ;
    }
}
