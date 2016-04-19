<?php

namespace Bigfoot\Bundle\SeoBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form which associate our Custom routes to X parameters
 * @Author S.Huot s.huot@c2is.fr
 */
class MetadataParameterType extends AbstractType
{

    protected $bigfootSeoManagerRoute;

    /**
     * Constructor
     */
    public function __construct($bigfootSeoManagerRoute)
    {
        $this->bigfootSeoManagerRoute = $bigfootSeoManagerRoute;
    }

    /**
     * Set the form made up of a route and X parameters
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routes = $this->bigfootSeoManagerRoute->getArrayRouteCollection();

        $builder
            ->add('route', ChoiceType::class, array(
                'choices' => array_flip($routes)
            ));


        $builder->add('parameters', CollectionType::class, array(
            'entry_type'   => ParameterType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,

        ));
    }

    /**
     * Set the default options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
