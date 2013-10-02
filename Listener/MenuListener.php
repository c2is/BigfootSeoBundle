<?php

namespace Bigfoot\Bundle\SeoBundle\Listener;

use Bigfoot\Bundle\CoreBundle\Event\MenuEvent;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;

class MenuListener
{
    /**
     * Add entry to the sidebar menu
     *
     * @param MenuEvent $event
     */
    function onMenuGenerate(MenuEvent $event)
    {
        $menu = $event->getMenu();

        if ($menu->getName() == 'sidebar_menu') {

            $menu->addItem(new Item('metadata', 'Seo','admin_seo_metadata', array(), array(), 'rocket'));
            $menu->addOnItem('sidebar_settings',new Item('sidebar_settings_metadata_parameter', 'Seo Parameters', 'admin_parameter_metadataparameter', array(), array(), 'rocket'));
        }

    }
}
