<?php

namespace Bigfoot\Bundle\SeoBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader;
use AppKernel;

/**
 * Uses the Symfony2 route loader to store a specific set of routes.
 *
 * The routes made available through this service are those for which a "label" option is set.
 * For your routes to be available, you must use the RouteManager::addBundle and pass the bundle name (eg: "BigfootCoreBundle").
 * All routes defined in the Controller/ directory in that bundle for which a "label" option is set will be loaded by the RouteManager.
 *
 * RouteManager
 * @package Bigfoot\Bundle\CoreBundle\Manager
 */
class RouteManager
{

    protected $router;
    protected $bigfootSeo;

    /**
     * Construct RouteManager
     *
     */
    public function __construct($router, $bigfootSeo)
    {
        $this->router      = $router;
        $this->bigfootSeo  = $bigfootSeo;
    }

    public function getArrayRouteCollection()
    {
        $routes  = $this->router->getRouteCollection();
        $nRoutes = array();

        foreach ($routes as $key => $route) {
            $routeOptions = $route->getOptions();

            if (isset($routeOptions['label'])) {
                $valueRoute = $key;

                if (strstr($valueRoute,'.')) {
                    $valueRoute = substr($valueRoute,0,strpos($valueRoute,'.'));
                }

                if (!array_key_exists($valueRoute,$nRoutes)) {
                    $nRoutes[$valueRoute] = $routeOptions['label'];
                }
            }
        }

        if ($this->bigfootSeo) {
            $nRoutes = array_merge($nRoutes,$this->bigfootSeo);
        }

        asort($nRoutes);

        return $nRoutes;
    }
}
