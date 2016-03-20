<?php

namespace moove\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PratiquerType extends AbstractType
{
    /**
     * Formulaire d'entité pratiqué
     * @param $builder <i>FormBuilderInterface<i/> 
     * @param $options <i>Array</i> 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('niveau', 'entity',
                ['label' => 'Niveau de l\'utilisateur',
                 'class' => 'mooveActiviteBundle:Niveau',
                 'property' => 'libelle'
                ]
            )
        ;
    }
    
    /**
     * @param <i>OptionsResolverInterface</i> $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'moove\ActiviteBundle\Entity\Pratiquer'
        ));
    }

    /**
     * retourn moove_activitebundle_pratiquer
     * @return <i>string</i>
     */
    public function getName()
    {
        return 'moove_activitebundle_pratiquer';
    }
}
