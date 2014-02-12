<?php

namespace Bigfoot\Bundle\SeoBundle\Controller;

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
use Bigfoot\Bundle\SeoBundle\Entity\Metadata;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataParameterRepository;
use Bigfoot\Bundle\SeoBundle\Form\MetadataType;

/**
 * Metadata controller.
 *
 * @Cache(maxage="0", smaxage="0", public="false")
 * @Route("/admin/seo/metadata")
 */
class MetadataController extends CrudController
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin_seo_metadata';
    }

    /**
     * @return string
     */
    protected function getEntity()
    {
        return 'BigfootSeoBundle:Metadata';
    }

    protected function getFields()
    {
        return array(
            'id'          => 'ID',
            'route'       => 'Route',
            'title'       => 'Title',
            'description' => 'Description',
            'keywords'    => 'Keywords'
        );
    }

    protected function getEntityLabelPlural()
    {
        return 'Metadata';
    }

    /**
     * Lists all parameters for a given route.
     *
     * @Route("/parameters/{route}", name="admin_seo_list_parameters", defaults={"route": null})
     * @Method("GET")
     * @Template("BigfootSeoBundle:Ajax:parameters.html.twig")
     */
    public function listParametersAction(Request $request, $route)
    {
        $parameters = array();

        $em = $this->container->get('doctrine')->getManager();

        $metadataParameter = $em->getRepository('BigfootSeoBundle:MetadataParameter')->findOneBy(array('route' => $route));
        if ($metadataParameter) {
            $tabParameters = $metadataParameter->getParameters();

            foreach ($tabParameters as $parameter) {
                $parameters[] = $parameter->getName();
            }
        }

        return array('parameters' => $parameters);
    }

    /**
     * Lists all Metadata entities.
     *
     * @Route("/", name="admin_seo_metadata")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }

    /**
     * Creates a new Metadata entity.
     *
     * @Route("/", name="admin_seo_metadata_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        return $this->doCreate($request);
    }

    /**
     * Displays a form to create a new Metadata entity.
     *
     * @Route("/new", name="admin_seo_metadata_new")
     * @Method("GET")
     * @Template("BigfootSeoBundle:Metadata:form.html.twig")
     */
    public function newAction()
    {
        $entity = new Metadata();
        $form   = $this->container->get('form.factory')->create('metadata', $entity);

        return array(
            'form'              => $form->createView(),
            'form_method'       => 'POST',
            'form_action'       => $this->container->get('router')->generate('admin_seo_metadata_create'),
            'form_submit'       => 'Submit',
            'form_title'        => 'Metadata creation',
            'form_cancel_route' => 'admin_seo_metadata',
            'parameters_url'    => $this->container->get('router')->generate('admin_seo_list_parameters'),
        );
    }

    /**
     * Displays a form to edit an existing Metadata entity.
     *
     * @Route("/{id}/edit", name="admin_seo_metadata_edit")
     * @Method("GET")
     * @Template("BigfootSeoBundle:Metadata:form.html.twig")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find Metadata entity.');
        }

        $editForm   = $this->container->get('form.factory')->create('metadata', $entity);

        return array(
            'form'              => $editForm->createView(),
            'form_method'       => 'PUT',
            'form_action'       => $this->container->get('router')->generate('admin_seo_metadata_update', array('id' => $entity->getId())),
            'form_submit'       => 'Edit',
            'form_title'        => 'Metadata edit',
            'form_cancel_route' => 'admin_seo_metadata',
            'parameters_url'    => $this->container->get('router')->generate('admin_seo_list_parameters'),
        );
    }

    /**
     * Edits an existing Metadata entity.
     *
     * @Route("/{id}", name="admin_seo_metadata_update")
     * @Method("PUT")
     * @Template("BigfootSeoBundle:Metadata:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->container->get('doctrine')->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find Metadata entity.');
        }

        $editForm = $this->container->get('form.factory')->create('metadata', $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return new RedirectResponse($this->container->get('router')->generate('admin_seo_metadata'));
        }

        return array(
            'form'              => $editForm->createView(),
            'form_method'       => 'PUT',
            'form_action'       => $this->container->get('router')->generate('admin_seo_metadata_update', array('id' => $entity->getId())),
            'form_submit'       => 'Edit',
            'form_title'        => 'Metadata edit',
            'form_cancel_route' => 'admin_seo_metadata',
        );
    }
    /**
     * Deletes a Metadata entity.
     *
     * @Route("/{id}", name="admin_seo_metadata_delete")
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }
}
