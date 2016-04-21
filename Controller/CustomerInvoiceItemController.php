<?php

namespace Flower\FinancesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Flower\FinancesBundle\Entity\CustomerInvoiceItem;
use Flower\FinancesBundle\Form\Type\CustomerInvoiceItemType;
use Doctrine\ORM\QueryBuilder;

/**
 * CustomerInvoiceItem controller.
 *
 * @Route("/finance/customer_invoice_item")
 */
class CustomerInvoiceItemController extends Controller
{
    /**
     * Lists all CustomerInvoiceItem entities.
     *
     * @Route("/", name="finance_customer_invoice_item")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('FlowerFinancesBundle:CustomerInvoiceItem')->createQueryBuilder('c');
        $this->addQueryBuilderSort($qb, 'customerinvoiceitem');
        $paginator = $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
        
        return array(
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a CustomerInvoiceItem entity.
     *
     * @Route("/{id}/show", name="finance_customer_invoice_item_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(CustomerInvoiceItem $customerinvoiceitem)
    {
        $editForm = $this->createForm(new CustomerInvoiceItemType(), $customerinvoiceitem, array(
            'action' => $this->generateUrl('finance_customer_invoice_item_update', array('id' => $customerinvoiceitem->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($customerinvoiceitem->getId(), 'finance_customer_invoice_item_delete');

        return array(

        'customerinvoiceitem' => $customerinvoiceitem,
        'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Displays a form to create a new CustomerInvoiceItem entity.
     *
     * @Route("/new", name="finance_customer_invoice_item_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $customerinvoiceitem = new CustomerInvoiceItem();
        $form = $this->createForm(new CustomerInvoiceItemType(), $customerinvoiceitem);

        return array(
            'customerinvoiceitem' => $customerinvoiceitem,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new CustomerInvoiceItem entity.
     *
     * @Route("/create", name="finance_customer_invoice_item_create")
     * @Method("POST")
     * @Template("FlowerFinancesBundle:CustomerInvoiceItem:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $customerinvoiceitem = new CustomerInvoiceItem();
        $form = $this->createForm(new CustomerInvoiceItemType(), $customerinvoiceitem);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerinvoiceitem);
            $em->flush();

            return $this->redirect($this->generateUrl('finance_customer_invoice_item_show', array('id' => $customerinvoiceitem->getId())));
        }

        return array(
            'customerinvoiceitem' => $customerinvoiceitem,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CustomerInvoiceItem entity.
     *
     * @Route("/{id}/edit", name="finance_customer_invoice_item_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(CustomerInvoiceItem $customerinvoiceitem)
    {
        $editForm = $this->createForm(new CustomerInvoiceItemType(), $customerinvoiceitem, array(
            'action' => $this->generateUrl('finance_customer_invoice_item_update', array('id' => $customerinvoiceitem->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($customerinvoiceitem->getId(), 'finance_customer_invoice_item_delete');

        return array(
            'customerinvoiceitem' => $customerinvoiceitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing CustomerInvoiceItem entity.
     *
     * @Route("/{id}/update", name="finance_customer_invoice_item_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("FlowerFinancesBundle:CustomerInvoiceItem:edit.html.twig")
     */
    public function updateAction(CustomerInvoiceItem $customerinvoiceitem, Request $request)
    {
        $editForm = $this->createForm(new CustomerInvoiceItemType(), $customerinvoiceitem, array(
            'action' => $this->generateUrl('finance_customer_invoice_item_update', array('id' => $customerinvoiceitem->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('finance_customer_invoice_item_show', array('id' => $customerinvoiceitem->getId())));
        }
        $deleteForm = $this->createDeleteForm($customerinvoiceitem->getId(), 'finance_customer_invoice_item_delete');

        return array(
            'customerinvoiceitem' => $customerinvoiceitem,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="finance_customer_invoice_item_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('customerinvoiceitem', $field, $type);

        return $this->redirect($this->generateUrl('finance_customer_invoice_item'));
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
     * Deletes a CustomerInvoiceItem entity.
     *
     * @Route("/{id}/delete", name="finance_customer_invoice_item_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(CustomerInvoiceItem $customerinvoiceitem, Request $request)
    {
        $form = $this->createDeleteForm($customerinvoiceitem->getId(), 'finance_customer_invoice_item_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerinvoiceitem);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('finance_customer_invoice_item'));
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
