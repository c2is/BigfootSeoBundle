<?php

namespace Bigfoot\Bundle\SeoBundle\Form;

use Bigfoot\Bundle\CoreBundle\Form\Type\TranslatedEntityType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form used to create some custom metadata parameters for a route
 * @Author S.Huot s.huot@c2is.fr
 */
class MetadataType extends AbstractType
{
    protected $bigfootSeoManagerRoute;

    /**
     * Constructor
     *
     * @param $container
     */
    public function __construct($bigfootSeoManagerRoute)
    {
        $this->bigfootSeoManagerRoute = $bigfootSeoManagerRoute;
    }

    /**
     * Creates the form made up of a route, a title, a description and some keywords
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routes = $this->bigfootSeoManagerRoute->getArrayRouteCollection();

        $builder
            ->add('route', ChoiceType::class, array(
                'choices' => array_flip($routes),
                'attr'    => array(
                    'class' => 'seo-metadata-route-choice',
                ),
            ));

        $builder
            ->add('title', ['required' => false])
            ->add('description', ['required' => false])
            ->add('keywords', TextareaType::class, ['required' => false])
            ->add('translation', TranslatedEntityType::class);
    }

    /**
     * Set the default options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
