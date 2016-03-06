<?php

namespace moove\ActiviteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PratiquerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'moove\ActiviteBundle\Entity\Pratiquer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'moove_activitebundle_pratiquer';
    }
}
