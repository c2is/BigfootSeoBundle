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
 * @Route("/admin/seo/parameter")
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
     * @Template("BigfootCoreBundle:Crud:index.html.twig")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }
    /**
     * Creates a new Parameter entity.
     *
     * @Route("/", name="admin_seo_parameter_create")
     * @Method("POST")
     * @Template("BigfootCoreBundle:Crud:form.html.twig")
     */
    public function createAction(Request $request)
    {

        return $this->doCreate($request);
    }

    /**
     * Displays a form to create a new Parameter entity.
     *
     * @Route("/new", name="admin_seo_parameter_new")
     * @Method("GET")
     * @Template("BigfootCoreBundle:Crud:form.html.twig")
     */
    public function newAction()
    {

        return $this->doNew();
    }

    /**
     * Displays a form to edit an existing Parameter entity.
     *
     * @Route("/{id}/edit", name="admin_seo_parameter_edit")
     * @Method("GET")
     * @Template("BigfootCoreBundle:Crud:form.html.twig")
     */
    public function editAction($id)
    {

        return $this->doEdit($id);
    }

    /**
     * Edits an existing Parameter entity.
     *
     * @Route("/{id}", name="admin_seo_parameter_update")
     * @Method("PUT")
     * @Template("BigfootCoreBundle:Crud:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        return $this->doUpdate($request, $id);
    }
    /**
     * Deletes a Parameter entity.
     *
     * @Route("/{id}", name="admin_seo_parameter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {

    return $this->doDelete($request, $id);
}
}
