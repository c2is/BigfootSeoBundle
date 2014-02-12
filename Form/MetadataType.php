<?php

namespace Bigfoot\Bundle\SeoBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form used to create some custom metadata parameters for a route
 * @Author S.Huot s.huot@c2is.fr
 */
class MetadataType extends AbstractType
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Creates the form made up of a route, a title, a description and some keywords
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routes = $this->container->get('bigfoot_core.manager.route')->getArrayRoutes();

        $builder
            ->add('route','choice',array(
                'choices' => $routes,
                'attr' => array(
                    'class' => 'seo-metadata-route-choice',
                ),
            ));

        $builder
            ->add('title')
            ->add('description')
            ->add('keywords')
            ->add('translation', 'translatable_entity')
        ;
    }

    /**
     * Set the default options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bigfoot\Bundle\SeoBundle\Entity\Metadata'
        ));
    }

    /**
     * Set the form name
     *
     * @return string
     */
    public function getName()
    {
        return 'metadata';
    }
}
