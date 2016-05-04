<?php

namespace Flower\FinancesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\DocumentItem;
use Flower\FinancesBundle\Form\Type\DocumentItemType;
use Doctrine\ORM\QueryBuilder;

/**
 * DocumentItem controller.
 *
 * @Route("/finance/document_item")
 */
class DocumentItemController extends Controller
{
    /**
     * Lists all DocumentItem entities.
     *
     * @Route("/", name="finance_document_item")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:DocumentItem')->createQueryBuilder('c');
        $this->addQueryBuilderSort($qb, 'documentitem');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a DocumentItem entity.
     *
     * @Route("/{id}/show", name="finance_document_item_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(DocumentItem $documentitem)
    {
        $editForm = $this->createForm(new DocumentItemType(), $documentitem, array(
            'action' => $this->generateUrl('finance_document_item_update', array('id' => $documentitem->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($documentitem->getId(), 'finance_document_item_delete');

        return array(

        'documentitem' => $documentitem,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new DocumentItem entity.
     *
     * @Route("/new", name="finance_document_item_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $documentitem = new DocumentItem();
        $form = $this->createForm(new DocumentItemType(), $documentitem);

        return array(
            'documentitem' => $documentitem,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new DocumentItem entity.
     *
     * @Route("/create", name="finance_document_item_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:DocumentItem:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $documentitem = new DocumentItem();
        $form = $this->createForm(new DocumentItemType(), $documentitem);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documentitem);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_document_item_show', array('id' => $documentitem->getId())));
        }

        return array(
            'documentitem' => $documentitem,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing DocumentItem entity.
     *
     * @Route("/{id}/edit", name="finance_document_item_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(DocumentItem $documentitem)
    {
        $editForm = $this->createForm(new DocumentItemType(), $documentitem, array(
            'action' => $this->generateUrl('finance_document_item_update', array('id' => $documentitem->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($documentitem->getId(), 'finance_document_item_delete');

        return array(
            'documentitem' => $documentitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing DocumentItem entity.
     *
     * @Route("/{id}/update", name="finance_document_item_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:DocumentItem:edit.html.twig")
     */
    public function updateAction(DocumentItem $documentitem, Request $request)
    {
        $editForm = $this->createForm(new DocumentItemType(), $documentitem, array(
            'action' => $this->generateUrl('finance_document_item_update', array('id' => $documentitem->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_document_item_show', array('id' => $documentitem->getId())));
        }
        $deleteForm = $this->createDeleteForm($documentitem->getId(), 'finance_document_item_delete');

        return array(
            'documentitem' => $documentitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_document_item_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('documentitem', $field, $type);

        return $this->redirect($this->generateUrl('finance_document_item'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Deletes a DocumentItem entity.
     *
     * @Route("/{id}/delete", name="finance_document_item_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(DocumentItem $documentitem, Request $request)
    {
        $form = $this->createDeleteForm($documentitem->getId(), 'finance_document_item_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documentitem);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_document_item'));
    }

    /**
     * Create Delete form
     *
     * @param integer                       $id
     * @param string                        $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
