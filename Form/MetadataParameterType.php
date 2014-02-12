<?php

namespace Bigfoot\Bundle\SeoBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form which associate our Custom routes to X parameters
 * @Author S.Huot s.huot@c2is.fr
 */
class MetadataParameterType extends AbstractType
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * Constructor
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Set the form made up of a route and X parameters
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routes = $this->container->get('bigfoot_core.manager.route')->getArrayRoutes();

        $builder
            ->add('route','choice',array(
                'choices' => $routes
            ));


        $builder->add('parameters', 'collection', array(
            'type' => new ParameterType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,

        ));
    }

    /**
     * Set the default options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bigfoot\Bundle\SeoBundle\Entity\MetadataParameter'
        ));
    }

    /**
     * Set the form name
     *
     * @return string
     */
    public function getName()
    {
        return 'metadataparameter';
    }
}
