<?php

namespace Bigfoot\Bundle\SeoBundle\Twig\Extension;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;
use Twig_Extension;
use Twig_Function_Method;
use Twig_Environment;
use BeSimple\I18nRoutingBundle\Routing\Router;
use mageekguy\atoum\tests\units\mock\php\method;

/**
 * SeoExtension
 */
class SeoExtension extends Twig_Extension
{
    private $entityManager;

    /**
     * Construct ContentExtension
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'seo_title'       => new Twig_Function_Method($this, 'seoTitle', array('is_safe' => array('html'))),
            'seo_description' => new Twig_Function_Method($this, 'seoDescription', array('is_safe' => array('html'))),
            'seo_keywords'    => new Twig_Function_Method($this, 'seoKeywords', array('is_safe' => array('html'))),
        );
    }

    public function seoTitle($route, $defaultKey, $entity = null)
    {
        $em = $this->entityManager;
        $metadata = $em->getRepository('BigfootSeoBundle:Metadata')->findOneBy(array('route' => $route));

        if (!$metadata) {
            $metadata = $em->getRepository('BigfootSeoBundle:Metadata')->findOneBy(array('route' => $defaultKey));
        }

        if ($metadata) {
            $title = $metadata->getTitle();
            $metadataParameter = $em->getRepository('BigfootSeoBundle:MetadataParameter')->findOneBy(array('route' => $route));
            if ($metadataParameter) {
                foreach ($metadataParameter->getParameters() as $parameter) {
                    if ($entity && strstr($title,$parameter->getName()) && method_exists($entity,$parameter->getMethod())) {
                        $strReplace = call_user_func(array($entity,$parameter->getMethod()));
                        $title = str_replace($parameter->getName(),$strReplace,$title);
                    }
                }
            }

            return $title;
        }

        return false;
    }

    public function seoDescription($route, $defaultKey, $entity = null)
    {
        $em = $this->entityManager;
        $metadata = $em->getRepository('BigfootSeoBundle:Metadata')->findOneBy(array('route' => $route));

        if (!$metadata) {
            $metadata = $em->getRepository('BigfootSeoBundle:Metadata')->findOneBy(array('route' => $defaultKey));
        }

        if ($metadata) {
            $description = $metadata->getDescription();
            $metadataParameter = $em->getRepository('BigfootSeoBundle:MetadataParameter')->findOneBy(array('route' => $route));
            if ($metadataParameter) {
                foreach ($metadataParameter->getParameters() as $parameter) {
                    if ($entity && strstr($description,$parameter->getName()) && method_exists($entity,$parameter->getMethod())) {
                        $strReplace = call_user_func(array($entity,$parameter->getMethod()));
                        $description = str_replace($parameter->getName(),$strReplace,$description);
                    }
                }
            }

            return $description;
        }

        return false;
    }

    public function seoKeywords($route, $defaultKey, $entity = null)
    {
        $em = $this->entityManager;
        $metadata = $em->getRepository('BigfootSeoBundle:Metadata')->findOneBy(array('route' => $route));

        if (!$metadata) {
            $metadata = $em->getRepository('BigfootSeoBundle:Metadata')->findOneBy(array('route' => $defaultKey));
        }

        if ($metadata) {
            $keywords = $metadata->getKeywords();
            $metadataParameter = $em->getRepository('BigfootSeoBundle:MetadataParameter')->findOneBy(array('route' => $route));
            if ($metadataParameter) {
                foreach ($metadataParameter->getParameters() as $parameter) {
                    if ($entity && strstr($keywords,$parameter->getName()) && method_exists($entity,$parameter->getMethod())) {
                        $strReplace = call_user_func(array($entity,$parameter->getMethod()));
                        $keywords = str_replace($parameter->getName(),$strReplace,$keywords);
                    }
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