<?php

namespace moove\ActiviteBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ActiviteAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('dateHeureRDV')
            ->add('duree')
            ->add('nbPlaces')
            ->add('description')
            ->add('dateCreation')
            ->add('dateFermeture')
            ->add('estTerminee')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('dateHeureRDV', 'datetime', array('label'=>'Date et heure de rendez-vous', 'format'=>'d/m/y'))
            ->add('duree')
            ->add('nbPlaces', 'number', array('label' => 'Nombre de places'))
            ->add('dateCreation', 'date', array('label' => 'Date de création', 'format' => 'd/m/y'))
            ->add('dateFermeture', 'date', array('label' => 'Date de fin d\'inscription', 'format' => 'd/m/y'))
            ->add('estTerminee')
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
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('dateHeureRDV', 'date', array('label'=>'Date et heure de rendez-vous'))
            ->add('duree')
            ->add('nbPlaces', 'number', array('label' => 'Nombre de places'))
            ->add('description', 'text', array('label' => 'Informations'))
            ->add('dateCreation', 'date', array('label' => 'Date de création'))
            ->add('dateFermeture', 'date', array('label' => 'Date de fin d\'inscription'))
            ->add('estTerminee')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('dateHeureRDV', 'date', array('label'=>'Date et heure de rendez-vous', 'format'=>'d/m/y'))
            ->add('duree')
            ->add('nbPlaces', 'number', array('label' => 'Nombre de places'))
            ->add('description', 'text', array('label' => 'Informations'))
            ->add('dateCreation', 'date', array('label' => 'Date de création', 'format' => 'd/m/y'))
            ->add('dateFermeture', 'date', array('label' => 'Date de fin d\'inscription', 'format' => 'd/m/y'))
            ->add('estTerminee')
        ;
    }
}
