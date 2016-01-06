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
 * @Route("/seo/metadata")
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
            'id'         => array(
                'label' => 'ID',
            ),
            'route'       => array(
                'label' => 'Route',
            ),
            'title'       => array(
                'label' => 'Title',
            ),
            'description' => array(
                'label' => 'Description',
            ),
            'keywords'    => array(
                'label' => 'Keywords',
            ),
        );
    }

    protected function getFormType()
    {
        return 'metadata';
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
        $em = $this->container->get('doctrine')->getManager();

        $metadataParameter = $em->getRepository('BigfootSeoBundle:MetadataParameter')->findOneByRoute($route);

        if ($metadataParameter) {
            return array(
                'metadataParameter' => $metadataParameter
            );
        }

       return array();
    }

    /**
     * Lists all Metadata entities.
     *
     * @Route("/", name="admin_seo_metadata")
     * @Method("GET")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->doIndex($request);
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
     * @Template("BigfootSeoBundle:Metadata:form.html.twig")
     */
    public function newAction(Request $request)
    {

        $entity = new Metadata();
        $form   = $this->createForm('metadata', $entity);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->prePersist($entity, 'new');

                $this->persistAndFlush($entity);

                if (!$request->isXmlHttpRequest()) {
                    $action = $this->generateUrl($this->getRouteNameForAction('edit'), array('id' => $entity->getId()));

                    $this->addSuccessFlash('The %entity% has been created.');

                    return $this->redirect($action);
                } else {
                    return $this->handleSuccessResponse('new', $entity);
                }
            }
        }

        return array(
            'form'              => $form->createView(),
            'form_method'       => 'POST',
            'form_action'       => $this->container->get('router')->generate('admin_seo_metadata_new'),
            'form_submit'       => 'Submit',
            'form_title'        => 'Metadata creation',
            'form_cancel_route' => 'admin_seo_metadata',
            'parameters_url'    => $this->container->get('router')->generate('admin_seo_list_parameters'),
        );
    }

    /**
     * Displays a form to edit an existing Metadata entity.
     *
     * @Route("/edit/{id}", name="admin_seo_metadata_edit")
     * @Template("BigfootSeoBundle:Metadata:form.html.twig")
     */
    public function editAction($id)
    {
        $entity = $this->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find Metadata entity.');
        }

        $editForm   = $this->createForm('metadata', $entity);

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
     * @Route("/update/{id}", name="admin_seo_metadata_update")
     * @Template("BigfootSeoBundle:Metadata:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->container->get('doctrine')->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find Metadata entity.');
        }

        $editForm   = $this->createForm('metadata', $entity);
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
     * @Route("/delete/{id}", name="admin_seo_metadata_delete")
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }
}
