<?php

namespace Bigfoot\Bundle\SeoBundle\Manager;

/**
 * Class SeoManager
 * @package Bigfoot\Bundle\SeoBundle\Manager
 */
class SeoManager
{
    /** @var object */
    private $object;

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param object $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }
}
