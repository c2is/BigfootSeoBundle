<?php

namespace Bigfoot\Bundle\SeoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Bigfoot\Bundle\SeoBundle\Entity\Parameter;
use Bigfoot\Bundle\SeoBundle\Form\ParameterType;

/**
 * Parameter controller.
 *
 * @Route("/admin/parameter/metadata/parameter")
 */
class ParameterController extends Controller
{

    /**
     * Lists all Parameter entities.
     *
     * @Route("/", name="admin_parameter_metadata_parameter")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BigfootSeoBundle:Parameter')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Parameter entity.
     *
     * @Route("/", name="admin_parameter_metadata_parameter_create")
     * @Method("POST")
     * @Template("BigfootSeoBundle:Parameter:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Parameter();
        $form = $this->createForm(new ParameterType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_parameter_metadata_parameter_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Parameter entity.
     *
     * @Route("/new", name="admin_parameter_metadata_parameter_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Parameter();
        $form   = $this->createForm(new ParameterType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Parameter entity.
     *
     * @Route("/{id}", name="admin_parameter_metadata_parameter_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Parameter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parameter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Parameter entity.
     *
     * @Route("/{id}/edit", name="admin_parameter_metadata_parameter_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Parameter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parameter entity.');
        }

        $editForm = $this->createForm(new ParameterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Parameter entity.
     *
     * @Route("/{id}", name="admin_parameter_metadata_parameter_update")
     * @Method("PUT")
     * @Template("BigfootSeoBundle:Parameter:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:Parameter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parameter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ParameterType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_parameter_metadata_parameter_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Parameter entity.
     *
     * @Route("/{id}", name="admin_parameter_metadata_parameter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BigfootSeoBundle:Parameter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Parameter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_parameter_metadata_parameter'));
    }

    /**
     * Creates a form to delete a Parameter entity by id.
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
