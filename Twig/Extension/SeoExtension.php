<?php

namespace Bigfoot\Bundle\SeoBundle\Twig\Extension;

use BeSimple\I18nRoutingBundle\Routing\Router;
use Bigfoot\Bundle\ContextBundle\Entity\ContextRepository;
use Bigfoot\Bundle\ContextBundle\Service\ContextService;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataParameterRepository;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataRepository;
use Bigfoot\Bundle\SeoBundle\Entity\Parameter;
use Bigfoot\Bundle\SeoBundle\Manager\SeoManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * SeoExtension
 */
class SeoExtension extends Twig_Extension
{
    /** @var \Doctrine\ORM\EntityManager */
    private $objectManager;

    /** @var \Bigfoot\Bundle\ContextBundle\Service\ContextService */
    private $context;

    /** @var \Bigfoot\Bundle\ContextBundle\Entity\ContextRepository */
    private $contextRepo;

    /** @var \Bigfoot\Bundle\SeoBundle\Manager\SeoManager */
    private $seoManager;

    /** @var \Symfony\Component\PropertyAccess\PropertyAccessor */
    private $propertyAccessor;

    /**
     * SeoExtension constructor.
     *
     * @param \Doctrine\ORM\EntityManager                            $objectManager
     * @param \Bigfoot\Bundle\ContextBundle\Service\ContextService   $context
     * @param \Bigfoot\Bundle\ContextBundle\Entity\ContextRepository $contextRepo
     * @param \Bigfoot\Bundle\SeoBundle\Manager\SeoManager           $seoManager
     * @param \Symfony\Component\PropertyAccess\PropertyAccessor     $propertyAccessor
     */
    public function __construct(
        EntityManager $objectManager,
        ContextService $context,
        ContextRepository $contextRepo,
        SeoManager $seoManager,
        PropertyAccessor $propertyAccessor
    ) {
        $this->entityManager    = $objectManager;
        $this->context          = $context;
        $this->contextRepo      = $contextRepo;
        $this->seoManager       = $seoManager;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('seo_title', [$this, 'seoTitle'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('seo_description', [$this, 'seoDescription'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('seo_keywords', [$this, 'seoKeywords'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|string $route
     * @param string|null                                      $defaultKey
     * @param object|null                                      $object
     *
     * @return bool|mixed|string
     */
    public function seoTitle($route, $defaultKey = null, $object = null)
    {
        if ($route instanceof Request) {
            $route = $route->attributes->get('_route');
        }

        if (null === $object) {
            $object = $this->seoManager->getObject();
        }

        if (method_exists($object, 'getSeoTitle') && ($title = $object->getSeoTitle())) {
            return $title;
        }

        $em       = $this->entityManager;
        $metadata = $this->getMetadata($route, $defaultKey);

        if ($metadata) {
            $title = $metadata->getTitle();
            /** @var MetadataParameterRepository $metaRepo */
            $metaRepo          = $em->getRepository('BigfootSeoBundle:MetadataParameter');
            $metadataParameter = $metaRepo->findOneByRoute($route);

            if ($object && $metadataParameter) {
                /** @var Parameter $parameter */
                foreach ($metadataParameter->getParameters() as $parameter) {
                    $title = $this->processParameter($parameter, $title, $object);
                }
            }

            return $title;
        }

        return false;
    }

    /**
     * @param      $route
     * @param null $defaultKey
     *
     * @return \Bigfoot\Bundle\SeoBundle\Entity\Metadata|null
     * @throws \Bigfoot\Bundle\ContextBundle\Exception\InvalidConfigurationException
     */
    public function getMetadata($route, $defaultKey = null)
    {
        $em                  = $this->entityManager;
        $contextRepo         = $this->contextRepo;
        $contextualizedQuery = $contextRepo->createContextQueryBuilder('Bigfoot\\Bundle\\SeoBundle\\Entity\\Metadata');
        /** @var MetadataRepository $metadataRepo */
        $metadataRepo = $em->getRepository('BigfootSeoBundle:Metadata');
        $metadata     = $metadataRepo->findOneByRoute($route, $contextualizedQuery);

        if (!$metadata && $defaultKey !== null) {
            $metadata = $metadataRepo->findOneByRoute($defaultKey, $contextualizedQuery);
        }

        return $metadata;
    }

    /**
     * @param \Bigfoot\Bundle\SeoBundle\Entity\Parameter $parameter
     * @param string                                     $string
     * @param object                                     $object
     *
     * @return mixed
     */
    private function processParameter(Parameter $parameter, $string, $object)
    {
        if (strstr($string, $parameter->getName())) {
            try {
                $value = $this->propertyAccessor->getValue($object, $parameter->getMethod());

                return str_replace('%'.trim($parameter->getName(), '%').'%', $value, $string);
            } catch (\Exception $e) {

            }
        }

        return $string;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|string $route
     * @param string|null                                      $defaultKey
     * @param object|null                                      $object
     *
     * @return bool|mixed|string
     */
    public function seoDescription($route, $defaultKey = null, $object = null)
    {
        if ($route instanceof Request) {
            $route = $route->attributes->get('_route');
        }

        if (null === $object) {
            $object = $this->seoManager->getObject();
        }

        $em       = $this->entityManager;
        $metadata = $this->getMetadata($route, $defaultKey);

        if (!$metadata) {
            /** @var MetadataRepository $metaRepo */
            $metaRepo = $em->getRepository('BigfootSeoBundle:Metadata');
            $metadata = $metaRepo->findOneByRoute($defaultKey);
        }

        if ($metadata) {
            $description = $metadata->getDescription();
            /** @var MetadataParameterRepository $metaParamRepo */
            $metaParamRepo     = $em->getRepository('BigfootSeoBundle:MetadataParameter');
            $metadataParameter = $metaParamRepo->findOneByRoute($route);

            if ($object && $metadataParameter) {
                /** @var Parameter $parameter */
                foreach ($metadataParameter->getParameters() as $parameter) {
                    $description = $this->processParameter($parameter, $description, $object);
                }
            }

            return $description;
        }

        return false;
    }

    /**
     * @param      $route
     * @param null $defaultKey
     * @param null $object
     *
     * @return bool|mixed|string
     */
    public function seoKeywords($route, $defaultKey = null, $object = null)
    {
        $em       = $this->entityManager;
        $metadata = $this->getMetadata($route, $defaultKey);

        if (null === $object) {
            $object = $this->seoManager->getObject();
        }

        if (!$metadata) {
            /** @var MetadataRepository $metaRepo */
            $metaRepo = $em->getRepository('BigfootSeoBundle:Metadata');
            $metadata = $metaRepo->findOneByRoute($defaultKey);
        }

        if ($metadata) {
            $keywords = $metadata->getKeywords();
            /** @var MetadataParameterRepository $metaParamRepo */
            $metaParamRepo     = $em->getRepository('BigfootSeoBundle:MetadataParameter');
            $metadataParameter = $metaParamRepo->findOneByRoute($route);

            if ($object && $metadataParameter) {
                /** @var Parameter $parameter */
                foreach ($metadataParameter->getParameters() as $parameter) {
                    $keywords = $this->processParameter($parameter, $keywords, $object);
                }
            }

            return $keywords;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_seo';
    }
}
