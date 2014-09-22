<?php

namespace Bigfoot\Bundle\SeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form of a parameter associate to a route
 * @Author S.Huot s.huot@c2is.fr
 */
class ParameterType extends AbstractType
{
    /**
     * Creates the form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('method')
        ;
    }

    /**
     * Set the defaults options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bigfoot\Bundle\SeoBundle\Entity\Parameter',
            'label'      => false,
        ));
    }

    /**
     * Set the name
     *
     * @return string
     */
    public function getName()
    {
        return 'bigfoot_bundle_seobundle_parametertype';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'bigfoot_collection_item';
    }
}
