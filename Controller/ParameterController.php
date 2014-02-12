<?php

namespace Bigfoot\Bundle\SeoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Bigfoot\Bundle\CoreBundle\Controller\CrudController;

/**
 * Parameter controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/seo/parameter")
 */
class ParameterController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_seo_parameter';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootSeoBundle:Parameter';
    }

    protected function getFields()
    {
        return array('id' => 'ID');
    }
    /**
     * Lists all Parameter entities.
     *
     * @Route("/", name="admin_seo_parameter")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }

    /**
     * Displays a form to create a new Parameter entity.
     *
     * @Route("/new", name="admin_seo_parameter_new")
     */
    public function newAction(Request $request)
    {
        return $this->doNew($request);
    }

    /**
     * Displays a form to edit an existing Parameter entity.
     *
     * @Route("/edit/{id}", name="admin_seo_parameter_edit")
     */
    public function editAction(Request $request, $id)
    {
        return $this->doEdit($request, $id);
    }

    /**
     * Deletes a Parameter entity.
     *
     * @Route("/delete/{id}", name="admin_seo_parameter_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }
}
