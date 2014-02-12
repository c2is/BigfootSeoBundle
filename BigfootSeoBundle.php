<?php

namespace Bigfoot\Bundle\SeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;

/**
 * Bundle BigfootSeo
 */
class BigfootSeoBundle extends Bundle
{
    public function boot()
    {
        $this->container->get('bigfoot_core.manager.route')->addBundle($this->getName());
    }
}
