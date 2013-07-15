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

/**
 * Metadata controller.
 *
 * @Route("/admin/seo/metadata")
 */
class MetadataController extends Controller
{

    /**
     * Lists all Metadata entities.
     *
     * @Route("/", name="admin_seo_metadata", options={"label"="Liste des Méta-données"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BigfootSeoBundle:Metadata')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Metadata entity.
     *
     * @Route("/", name="admin_seo_metadata_create", options={"label"="Create Route"})
     * @Method("POST")
     * @Template("BigfootSeoBundle:Metadata:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $route_name = $request->get('route_name');

            $em = $this->getDoctrine()->getManager();

            $metadataParameter = $em->getRepository('BigfootSeoBundle:MetadataParameter')->findOneBy(array('route' => $route_name));

            if ($metadataParameter) {
                $tabParameters = $metadataParameter->getParameters();

                $tabReturn = array();

                foreach ($tabParameters as $parameter) {
                    $tabReturn[] = $parameter->getName();
                }

                return new Response($this->container->get('templating')->render('BigfootSeoBundle:Ajax:parameters.html.twig',array('tabParameters' => $tabReturn)));
            }
            else {
                return array();
            }
        }

        $entity  = new Metadata();
        $form = $this->createForm('metadata', $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_seo_metadata_show', array('id' => $entity->getId())));
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
     * @Template()
     */
    public function newAction()
    {
        $entity = new Metadata();
        $form   = $this->createForm('metadata', $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

            return $this->redirect($this->generateUrl('admin_seo_metadata_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
