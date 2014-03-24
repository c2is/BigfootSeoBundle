<?php

namespace Bigfoot\Bundle\SeoBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\CrudController;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataParameter;
use Bigfoot\Bundle\SeoBundle\Entity\Parameter;
use Bigfoot\Bundle\SeoBundle\Form\MetadataParameterType;

/**
 * MetadataParameter controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/seo/parameter/metadataparameter")
 */
class MetadataParameterController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_parameter_metadataparameter';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootSeoBundle:MetadataParameter';
    }

    protected function getFields()
    {
        return array(
            'id'                => 'ID',
            'route'             => 'Route',
            'getFirstParameter' => 'Parameters',
        );
    }

    protected function getFormType()
    {
        return 'metadataparameter';
    }

    protected function getEntityLabelPlural()
    {
        return 'Metadata parameters';
    }

    /**
     * Lists all MetadataParameter entities.
     *
     * @Route("/", name="admin_parameter_metadataparameter")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }

    /**
     * Displays a form to create a new MetadataParameter entity.
     *
     * @Route("/new", name="admin_parameter_metadataparameter_new")
     */
    public function newAction(Request $request)
    {
        return $this->doNew($request);
    }

    /**
     * Displays a form to edit an existing MetadataParameter entity.
     *
     * @Route("/edit/{id}", name="admin_parameter_metadataparameter_edit")
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEdit($request, $id);
    }

    /**
     * Deletes a MetadataParameter entity.
     *
     * @Route("/delete/{id}", name="admin_parameter_metadataparameter_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }
}
