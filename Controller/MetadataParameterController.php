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
 * @Route("/admin/parameter/metadataparameter")
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

    protected function getEntityLabelPlural()
    {
        return 'Metadata parameters';
    }

    /**
     * Lists all MetadataParameter entities.
     *
     * @Route("/", name="admin_parameter_metadataparameter")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->doIndex();
    }

    /**
     * Creates a new MetadataParameter entity.
     *
     * @Route("/", name="admin_parameter_metadataparameter_create")
     * @Method("POST")
     * @Template("BigfootSeoBundle:MetadataParameter:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MetadataParameter();
        $form   = $this->container->get('form.factory')->create('metadataparameter', $entity);
        $form->submit($request);

        if ($form->isValid()) {

            $post  = $request->request->get('metadataparameter');
            $route = $post['route'];

            $uniqueRoute = $this->getRepository('BigfootSeoBundle:MetadataParameter')->findOneBy(array('route' => $route));

            if ($uniqueRoute) {

                $form->get('route')->addError(new FormError('Des paramètres ont déjà été créés pour cette route'));

                return array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
                );
            }

            $this->persistAndFlush($entity);

            return new RedirectResponse($this->container->get('router')->generate('admin_parameter_metadataparameter'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new MetadataParameter entity.
     *
     * @Route("/new", name="admin_parameter_metadataparameter_new")
     * @Method("GET")
     * @Template("BigfootSeoBundle:MetadataParameter:form.html.twig")
     */
    public function newAction()
    {
        $entity = new MetadataParameter();
        $form   = $this->container->get('form.factory')->create('metadataparameter', $entity);

        return array(
            'form'              => $form->createView(),
            'form_action'       => $this->container->get('router')->generate('admin_parameter_metadataparameter_create', array('id' => $entity->getId())),
            'form_method'       => 'POST',
            'form_title'        => 'MetadataParameter creation',
            'form_cancel_route' => 'admin_parameter_metadataparameter',
        );
    }

    /**
     * Displays a form to edit an existing MetadataParameter entity.
     *
     * @Route("/{id}/edit", name="admin_parameter_metadataparameter_edit")
     * @Method("GET")
     * @Template("BigfootSeoBundle:MetadataParameter:form.html.twig")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->container->get('doctrine')->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:MetadataParameter')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find MetadataParameter entity.');
        }

        $originalParameters = array();

        // Crée un tableau contenant les objets Tag courants de la
        // base de données
        foreach ($entity->getParameters() as $parameter) $originalParameters[] = $parameter;

        $form = $this->container->get('form.factory')->create('metadataparameter', $entity);

        if ($request->isMethod('POST')) {
            $form->submit($this->getRequest());

            if ($form->isValid()) {

                // filtre $originalTags pour ne contenir que les tags
                // n'étant plus présents
                foreach ($entity->getParameters() as $parameter) {
                    foreach ($originalParameters as $key => $toDel) {
                        if ($toDel->getId() === $parameter->getId()) {
                            unset($originalParameters[$key]);
                        }
                    }
                }

                // supprime la relation entre le tag et la « Task »
                foreach ($originalParameters as $parameter) {
                    // supprime la « Task » du Tag
                    $parameter->getMetadataParameters()->removeElement($entity);

                    // si c'était une relation ManyToOne, vous pourriez supprimer la
                    // relation comme ceci
                    // $tag->setTask(null);

                    $em->persist($parameter);

                    // si vous souhaitiez supprimer totalement le Tag, vous pourriez
                    // aussi faire comme cela
                    // $em->remove($tag);
                }

                $em->persist($entity);
                $em->flush();

                // redirige vers quelconque page d'édition
                return new RedirectResponse($this->container->get('router')->generate('metadataparameter_edit', array('id' => $id)));
            }
        }

        $form = $this->container->get('form.factory')->create('metadataparameter', $entity);

        return array(
            'entity'       => $entity,
            'form'         => $form->createView(),
            'form_method'  => $request->getMethod(),
            'form_title'   => sprintf('%s creation', $this->getEntityLabel()),
            'form_action'  => $this->generateUrl($this->getRouteNameForAction('create')),
            'form_submit'  => 'Create',
            'cancel_route' => $this->getRouteNameForAction('index'),
            'isAjax'       => $request->isXmlHttpRequest(),
            'breadcrumbs'  => array(
                array(
                    'url'   => $this->generateUrl($this->getRouteNameForAction('index')),
                    'label' => $this->getEntityLabelPlural()
                ),
                array(
                    'url'   => $this->generateUrl($this->getRouteNameForAction('new')),
                    'label' => sprintf('%s creation', $this->getEntityLabel())
                ),
            ),
        );
    }

    /**
     * Edits an existing MetadataParameter entity.
     *
     * @Route("/{id}", name="admin_parameter_metadataparameter_update")
     * @Method("PUT")
     * @Template("BigfootSeoBundle:MetadataParameter:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->container->get('doctrine')->getManager();

        $entity = $em->getRepository('BigfootSeoBundle:MetadataParameter')->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find MetadataParameter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->container->get('form.factory')->create('metadataparameter', $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return new RedirectResponse($this->container->get('router')->generate('admin_parameter_metadataparameter_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MetadataParameter entity.
     *
     * @Route("/delete/{id}", name="admin_parameter_metadataparameter_delete")
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->doDelete($request, $id);
    }
}
