<?php

namespace Bigfoot\Bundle\SeoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bigfoot\Bundle\SeoBundle\Entity\Metadata;
use Bigfoot\Bundle\SeoBundle\Entity\MetadataParameterRepository;
use Bigfoot\Bundle\SeoBundle\Form\MetadataType;
use Bigfoot\Bundle\CoreBundle\Theme\Menu\Item;

/**
 * Metadata controller.
 *
 * @Route("/admin/seo/metadata")
 */
class MetadataController extends Controller
{
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

        $em = $this->getDoctrine()->getManager();

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
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BigfootSeoBundle:Metadata')->findAll();

        $theme = $this->container->get('bigfoot.theme');
        $theme['page_content']['globalActions']->addItem(new Item('crud_add', 'Add a metadata', 'admin_seo_metadata_new'));

        return array(
            'list_items'        => $entities,
            'list_edit_route'   => 'admin_seo_metadata_edit',
            'list_title'        => 'Metadata list',
            'list_fields'       => array('id' => 'ID', 'route' => 'Route', 'title'=> 'Title', 'description' => 'Description', 'keywords' => 'Keywords'),
        );
    }

    /**
     * Creates a new Metadata entity.
     *
     * @Route("/", name="admin_seo_metadata_create")
     * @Method("POST")
     * @Template("BigfootSeoBundle:Metadata:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Metadata();
        $form = $this->createForm('metadata', $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_seo_metadata'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Metadata entity.
     *
     * @Route("/new", name="admin_seo_metadata_new")
     * @Method("GET")
     * @Template("BigfootSeoBundle:Metadata:edit.html.twig")
     */
    public function newAction()
    {
        $entity = new Metadata();
        $form   = $this->createForm('metadata', $entity);

        return array(
            'form'              => $form->createView(),
            'form_method'       => 'POST',
            'form_action'       => $this->generateUrl('admin_seo_metadata_create'),
            'form_submit'       => 'Submit',
            'form_title'        => 'Metadata creation',
            'form_cancel_route' => 'admin_seo_metadata',
            'parameters_url'    => $this->generateUrl('admin_seo_list_parameters'),
        );
    }

    /**
     * Finds and displays a Metadata entity.
     *
     * @Route("/{id}", name="admin_seo_metadata_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Metadata entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Metadata entity.
     *
     * @Route("/{id}/edit", name="admin_seo_metadata_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Metadata entity.');
        }

        $editForm = $this->createForm('metadata', $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'form'                  => $editForm->createView(),
            'form_method'           => 'PUT',
            'form_action'           => $this->generateUrl('admin_seo_metadata_update', array('id' => $entity->getId())),
            'form_submit'           => 'Edit',
            'form_title'            => 'Metadata edit',
            'form_cancel_route'     => 'admin_seo_metadata',
            'delete_form'           => $deleteForm->createView(),
            'delete_form_action'    =>  $this->generateUrl('admin_seo_metadata_delete', array('id' => $entity->getId())),
            'parameters_url'        => $this->generateUrl('admin_seo_list_parameters'),
        );
    }

    /**
     * Edits an existing Metadata entity.
     *
     * @Route("/{id}", name="admin_seo_metadata_update")
     * @Method("PUT")
     * @Template("BigfootSeoBundle:Metadata:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Metadata')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Metadata entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm('metadata', $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_seo_metadata'));
        }

        return array(
            'form'              => $editForm->createView(),
            'form_method'       => 'PUT',
            'form_action'       => $this->generateUrl('admin_seo_metadata_update', array('id' => $entity->getId())),
            'form_submit'       => 'Edit',
            'form_title'        => 'Metadata edit',
            'form_cancel_route' => 'admin_seo_metadata',
            'delete_form'       => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Metadata entity.
     *
     * @Route("/{id}", name="admin_seo_metadata_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BigfootSeoBundle:Metadata')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Metadata entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_seo_metadata'));
    }

    /**
     * Creates a form to delete a Metadata entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
