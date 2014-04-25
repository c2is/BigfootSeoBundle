<?php

namespace Bigfoot\Bundle\SeoBundle\Subscriber;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Bigfoot\Bundle\CoreBundle\Event\MenuEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Menu Subscriber
 */
class MenuSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security;

    /**
     * @param SecurityContextInterface $security
     */
    public function __construct(SecurityContextInterface $security)
    {
        $this->security = $security;
    }

    /**
     * Get subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            MenuEvent::GENERATE_MAIN => array('onGenerateMain', 3)
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onGenerateMain(GenericEvent $event)
    {
        $menu = $event->getSubject();
        $root = $menu->getRoot();

        if ($this->security->isGranted('ROLE_ADMIN') or $this->security->isGranted('ROLE_SEO')) {
            $seoMenu = $root->addChild(
                'seo',
                array(
                    'label'          => 'Seo',
                    'url'            => '#',
                    'linkAttributes' => array(
                        'class' => 'dropdown-toggle',
                        'icon'  => 'rocket',
                    )
                )
            );

            $seoMenu->setChildrenAttributes(
                array(
                    'class' => 'submenu',
                )
            );

            $seoMenu->addChild(
                'metadata',
                array(
                    'label'  => 'Metadata',
                    'route'  => 'admin_seo_metadata',
                    'extras' => array(
                        'routes' => array(
                            'admin_seo_metadata_new',
                            'admin_seo_metadata_edit'
                        )
                    ),
                    'linkAttributes' => array(
                        'icon' => 'rocket',
                    )
                )
            );
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $seoMenu->addChild(
                'metadata_parameter',
                array(
                    'label'  => 'Metadata Parameter',
                    'route'  => 'admin_parameter_metadataparameter',
                    'extras' => array(
                        'routes' => array(
                            'admin_parameter_metadataparameter_new',
                            'admin_parameter_metadataparameter_edit'
                        )
                    ),
                    'linkAttributes' => array(
                        'icon' => 'rocket',
                    )
                )
            );
        }
    }
}
