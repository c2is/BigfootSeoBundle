<?php

namespace Bigfoot\Bundle\SeoBundle\Subscriber;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Bigfoot\Bundle\CoreBundle\Event\MenuEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Menu Subscriber
 */
class MenuSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorage
     */
    private $security;

    /**
     * @param TokenStorage $security
     */
    public function __construct(TokenStorage $security)
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
        $builder = $event->getSubject();

        $builder
            ->addChild(
                'seo',
                array(
                    'label'          => 'Seo',
                    'url'            => '#',
                    'attributes' => array(
                        'class' => 'parent',
                    ),
                    'linkAttributes' => array(
                        'class' => 'dropdown-toggle',
                        'icon'  => 'rocket',
                    )
                ),
                array(
                    'children-attributes' => array(
                        'class' => 'submenu'
                    )
                )
            )
            ->addChildFor(
                'seo',
                'seo_metadata',
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
            )
            ->addChildFor(
                'seo',
                'seo_metadata_parameter',
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
