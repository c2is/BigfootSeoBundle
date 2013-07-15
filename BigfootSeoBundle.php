<?php

namespace Bigfoot\Bundle\SeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;

/**
 * Bundle BigfootSeo
 */
class BigfootSeoBundle extends Bundle
{
    /**
     * Add the entries to the back-office menu
     */
    public function boot()
    {
//        $this->container->get('theme')['sidebar']['menu']->addItem(new Item('metadata', 'Seo','admin_seo_metadata'));
//        $this->container->get('theme')['sidebar']['menu']->addOnItem('sidebar_settings',new Item('sidebar_settings_metadata_parameter', 'Seo Parameters','admin_parameter_metadataparameter'));
    }
}
