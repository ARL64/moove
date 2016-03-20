<?php

namespace moove\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use moove\ActiviteBundle\Validator\Constraints\Adresse;

class ActiviteType extends AbstractType
{

    /**
     * Formulaire d'entité activité
     * @param $builder <i>FormBuilderInterface<i/> 
     * @param $options <i>Array</i> 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = getDate();
        //$jour = $today['wday'];
        $annee = $today['year'];
        
        $builder
                ->add('sportPratique', 'entity',
                      array('label' => 'Sport',
                            'empty_value' => 'Sélectionnez un sport',
                            'class' => 'mooveActiviteBundle:Sport',
                            'property' => 'nom',
                            'multiple' => false,
                            'expanded' => false))
                ->add('niveauRequis', 'entity',
                      array('label' => 'Niveau requis',
                            'empty_value' => 'Sélectionez un niveau',
                            'class' => 'mooveActiviteBundle:Niveau',
                            'property' => 'libelle',
                            'multiple' => false,
                            'expanded' => false))
               ->add('dateHeureRDV', 'datetime', array('label' => 'Date et heure de rendez-vous','years' => range($annee, ($annee+5))))
               ->add('dateFermeture', 'datetime', array('label' => 'Date et heure de fermeture  de l\'activité', 'years' => range($annee, ($annee+5))))
               ->add('duree', 'time', array('label' => 'Durée estimée'))
               ->add('nbPlaces','integer', array('label'=> 'Nombre de places total (vous inclus)'))
               //->add('description', 'textarea', array ('required' => false,'label' => 'Informations'))
               ->add('description', 'ckeditor', array ('required' => false, 'label' => 'Informations', 'config_name' => 'config_description'))
               ->add('adresseLieuRDV', 'text', array('constraints' => new Adresse()))
               ->add('adresseLieuDepart', 'text', array('required' => false, 'constraints' => new Adresse()))
               ->add('adresseLieuArrivee', 'text', array('required' => false, 'constraints' => new Adresse()))
        ;
    }
    

    /**
     * @param $resolver <i>OptionsResolverInterface</i> 
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'moove\ActiviteBundle\Entity\Activite'
        ));
    }
    
    /**
     * retourn moove_activitebundle_activite
     * @return <i>string</i>
     */
    public function getName()
    {
        return 'moove_activitebundle_activite';
    }
}
