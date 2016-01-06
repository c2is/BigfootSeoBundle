<?php

namespace Bigfoot\Bundle\SeoBundle\Twig\Extension;

use Bigfoot\Bundle\ContextBundle\Entity\ContextRepository;
use Bigfoot\Bundle\ContextBundle\Service\ContextService;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataParameterRepository;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataRepository;
use Bigfoot\Bundle\SeoBundle\Entity\Parameter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_Environment;
use BeSimple\I18nRoutingBundle\Routing\Router;

/**
 * SeoExtension
 */
class SeoExtension extends Twig_Extension
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    /** @var \Bigfoot\Bundle\ContextBundle\Service\ContextService */
    private $context;

    /** @var \Bigfoot\Bundle\ContextBundle\Entity\ContextRepository */
    private $contextRepo;

    /**
     * Construct ContentExtension
     */
    public function __construct(EntityManager $entityManager, ContextService $context, ContextRepository $contextRepo)
    {
        $this->entityManager = $entityManager;
        $this->context       = $context;
        $this->contextRepo   = $contextRepo;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('seo_title', array($this, 'seoTitle'), array('is_safe' => array('html'))),
            new Twig_SimpleFunction('seo_description', array($this, 'seoDescription'), array('is_safe' => array('html'))),
            new Twig_SimpleFunction('seo_keywords', array($this, 'seoKeywords'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param      $route
     * @param null $defaultKey
     * @param null $entity
     *
     * @return bool|mixed|string
     */
    public function seoTitle($route, $defaultKey = null, $entity = null)
    {
        $em = $this->entityManager;
        $metadata = $this->getMetadata($route, $defaultKey);

        if ($metadata) {
            $title = $metadata->getTitle();
            /** @var MetadataParameterRepository $metaRepo */
            $metaRepo = $em->getRepository('BigfootSeoBundle:MetadataParameter');
            $metadataParameter = $metaRepo->findOneByRoute($route);

            if ($metadataParameter) {
                /** @var Parameter $parameter */
                foreach ($metadataParameter->getParameters() as $parameter) {
                    if ($entity && strstr($title, $parameter->getName()) && method_exists($entity, $parameter->getMethod())) {
                        $strReplace = call_user_func(array($entity, $parameter->getMethod()));
                        $title = str_replace($parameter->getName(), $strReplace, $title);
                    }
                }
            }

            return $title;
        }

        return false;
    }

    /**
     * @param      $route
     * @param null $defaultKey
     * @param null $entity
     *
     * @return bool|mixed|string
     */
    public function seoDescription($route, $defaultKey = null, $entity = null)
    {
        $em = $this->entityManager;
        $metadata = $this->getMetadata($route, $defaultKey);

        if (!$metadata) {
            /** @var MetadataRepository $metaRepo */
            $metaRepo = $em->getRepository('BigfootSeoBundle:Metadata');
            $metadata = $metaRepo->findOneByRoute($defaultKey);
        }

        if ($metadata) {
            $description = $metadata->getDescription();
            /** @var MetadataParameterRepository $metaParamRepo */
            $metaParamRepo = $em->getRepository('BigfootSeoBundle:MetadataParameter');
            $metadataParameter = $metaParamRepo->findOneByRoute($route);

            if ($metadataParameter) {
                /** @var Parameter $parameter */
                foreach ($metadataParameter->getParameters() as $parameter) {
                    if ($entity && strstr($description, $parameter->getName()) && method_exists($entity, $parameter->getMethod())) {
                        $strReplace = call_user_func(array($entity, $parameter->getMethod()));
                        $description = str_replace($parameter->getName(), $strReplace, $description);
                    }
                }
            }

            return $description;
        }

        return false;
    }

    /**
     * @param      $route
     * @param null $defaultKey
     * @param null $entity
     *
     * @return bool|mixed|string
     */
    public function seoKeywords($route, $defaultKey = null, $entity = null)
    {
        $em = $this->entityManager;
        $metadata = $this->getMetadata($route, $defaultKey);

        if (!$metadata) {
            /** @var MetadataRepository $metaRepo */
            $metaRepo = $em->getRepository('BigfootSeoBundle:Metadata');
            $metadata = $metaRepo->findOneByRoute($defaultKey);
        }

        if ($metadata) {
            $keywords = $metadata->getKeywords();
            /** @var MetadataParameterRepository $metaParamRepo */
            $metaParamRepo = $em->getRepository('BigfootSeoBundle:MetadataParameter');
            $metadataParameter = $metaParamRepo->findOneByRoute($route);

            if ($metadataParameter) {
                /** @var Parameter $parameter */
                foreach ($metadataParameter->getParameters() as $parameter) {
                    if ($entity && strstr($keywords, $parameter->getName()) && method_exists($entity, $parameter->getMethod())) {
                        $strReplace = call_user_func(array($entity, $parameter->getMethod()));
                        $keywords = str_replace($parameter->getName(), $strReplace, $keywords);
                    }
                }
            }

            return $keywords;
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
        $em = $this->entityManager;
        $contextRepo = $this->contextRepo;
        $contextualizedQuery = $contextRepo->createContextQueryBuilder('Bigfoot\\Bundle\\SeoBundle\\Entity\\Metadata');
        /** @var MetadataRepository $metadataRepo */
        $metadataRepo = $em->getRepository('BigfootSeoBundle:Metadata');
        $metadata = $metadataRepo->findOneByRoute($route, $contextualizedQuery);

        if (!$metadata && $defaultKey !== null) {
            $metadata = $metadataRepo->findOneByRoute($defaultKey, $contextualizedQuery);
        }

        return $metadata;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_seo';
    }
}
