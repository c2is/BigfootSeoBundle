<?php

namespace Bigfoot\Bundle\SeoBundle\Helper;

use Bigfoot\Bundle\SeoBundle\Manager\RouteManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SeoHelper
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array Default meta data values.
     */
    protected $default;

    /**
     * @var array The name of the fields managed by this bundle.
     */
    protected $seoFields;

    /**
     * Constructor.
     *
     * @param Container $container
     * @param RequestStack $requestStack
     */
    public function __construct(Container $container, RequestStack $requestStack)
    {
        $this->twig = $container->get('twig');

        $seoConfig = $container->getParameter('bigfoot_seo');
        $this->default = array(
            'meta_title' => 'Bigfoot Front Office',
            'meta_description' => '',
            'meta_keywords' => '',
        );
        if (isset($seoConfig['default'])) {
            $this->default = array_merge($this->default, $seoConfig['default']);
        }

        $this->seoFields = array(
            'title',
            'description',
            'keywords',
        );

        $this->request = $requestStack->getCurrentRequest();

        if (null === $this->request) {
            $this->request = new Request();
        }

        $this->entityManager = $container->get('doctrine')->getManager();

        $this->routeManager = $container->get('bigfoot_seo.manager.route');
    }

    public function getMetadata($fieldname = null, $route = null)
    {
        if ($fieldname and !in_array($fieldname, $this->seoFields)) {
            throw new Exception(sprintf("%s fieldname does not exist (allowed values: %s)",
                $fieldname, implode(', ', $this->seoFields)
            ));
        }

        if (!$route) {
            $route = $this->request->get('_route');
        }

        $routes = $this->routeManager->getArrayRouteCollection()->toArray();

        if (array_key_exists($route, $routes)) {
            $repository = $this->entityManager->getRepository('BigfootSeoBundle:Metadata');
            $metadata = $repository->findOneByRoute($route);

            if ($metadata) {
                if ($fieldname) {
                    $fieldValue = call_user_func(array($metadata, 'get'.ucfirst($fieldname)));

                    return $fieldValue ?: $this->default['meta_'.$fieldname];
                }

                return $metadata;
            }
        }

        return null;
    }

    public function render($route = null)
    {
        $title       = $this->default['meta_title'];
        $description = $this->default['meta_description'];
        $keywords    = $this->default['meta_keywords'];

        $metadata = $this->getMetadata(null, $route);

        if ($metadata) {
            $title       = $metadata->getTitle();
            $description = $metadata->getDescription();
            $keywords    = $metadata->getKeywords();
        }

        return $this->twig->render('BigfootSeoBundle:front:seo_front.html.twig', array(
            'meta_title'       => $title,
            'meta_description' => $description,
            'meta_keywords'    => $keywords,
        ));
    }
}
